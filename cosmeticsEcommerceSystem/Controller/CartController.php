<?php
function getUserCart($userId){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql  = "
    SELECT product._id, product.name, product.price, product.discount, product.picture, product.description, cart.qty AS quantity , cart._id AS cId
    FROM product
    JOIN cart ON product._id = cart.productId
    WHERE cart.userId = $userId
    ";
    
    // Execute the SQL query
    $result = $mysqli->query($sql);

    // Check if there are any rows returned
    if ($result->num_rows == 0) {
        // Return an empty array if the cart is empty
        return [];
    }

    // Create an empty array to store cart items
    $cartItems = [];

    // Iterate over the result set
    while ($row = $result->fetch_assoc()) {
        // Create a Product object
        $product = new Product(
            $row['_id'],
            $row['name'],
            $row['price'],
            $row['discount'],
            null, // You may need to fetch the category if available
            $row['picture'],
            $row['description']
        );

        // Create a Cart object with the Product object and quantity
        $cartItem = new Cart($row['cId'] , $product, $row['quantity']);

        // Append the Cart object to the array
        $cartItems[] = $cartItem;
    }

    // Return the array of Cart objects
    return $cartItems;
}
function getProductCount($productId) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = "SELECT count FROM product WHERE _id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $productId); // Assuming _id is an integer
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        return $row['count'];
    } else {
        // Handle error if query fails or product not found
        return 0;
    }
}
function addToCart($productId, $userId){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    
    // Retrieve the count of the product
    $count = getProductCount($productId);

    if ($count == 0) {
        return false; // Product not available
    }
    
    // Check if the product already exists in the cart
    $sql = "SELECT qty FROM cart WHERE productId = ? AND userId = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $productId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        if ($row !== null && isset($row['qty']) && $row['qty'] >= $count) {
            return false; // Not enough items available
        }
    } else {
        // Log the error message
        error_log("Error in executing SQL query: " . $stmt->error);
        return false; // Error in executing SQL query
    }


    // Prepare the SQL statement
    $sql = "INSERT INTO cart (productId, userId, qty) 
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE qty = qty + 1";

    // Prepare the statement
    $stmt = $mysqli->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ii", $productId, $userId);

    // Execute the statement
    $stmt->execute();

    // Check if the query was successful
    if ($stmt->affected_rows > 0) {
        // Return true if successful
        return true;
    } else {
        // Return false if unsuccessful
        return false;
    }
}
function deleteItem($itemId, $userId){ // deleteItem(cartItem)
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // Prepare the SQL statement
    $sql = "DELETE FROM cart WHERE productId = ? AND userId = ?";// DELETE FROM cart WHERE _id = ? 
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // If preparation fails, return false
        return false;
    }

    // Bind parameters
    $stmt->bind_param("ii", $itemId, $userId);

    // Execute the statement
    $stmt->execute();

    // Check if any rows were affected (if the deletion was successful)
    if ($stmt->affected_rows > 0) {
        // Return true if successful
        return true;
    } else {
        // Return false if unsuccessful
        return false;
    }
}
function countItems($userId){
    $item_count = 0;
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // Prepare the SQL statement
    $sql = "SELECT COUNT(*) AS item_count FROM cart WHERE userId = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // If preparation fails, return -1
        return -1;
    }

    // Bind parameters
    $stmt->bind_param("i", $userId);

    // Execute the statement
    $stmt->execute();

    // Bind the result
    $stmt->bind_result($item_count);

    // Fetch the result
    $stmt->fetch();

    // Close the statement
    $stmt->close();

    // Return the item count
    return $item_count;
}
function updateItemQty($itemId, $userId, $qty){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    
    // Retrieve the count of the product
    $count = getProductCount($itemId);

    // Check if requested quantity exceeds available quantity
    if ($qty > $count) {
        // Return false if requested quantity exceeds available quantity
        return false;
    }

    // Prepare the SQL statement to update cart
    $sql = "UPDATE cart SET qty = ? WHERE userId = ? AND productId = ?";
    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        // If preparation fails, return false
        return false;
    }

    // Bind parameters
    $stmt->bind_param("iii", $qty, $userId, $itemId);

    // Execute the statement
    $stmt->execute();

    // Check if any rows were affected (if the update was successful)
    if ($stmt->affected_rows > 0) {
        // Return true if successful
        return true;
    } else {
        // Return false if unsuccessful
        return false;
    }
}
function deleteCart($userId) {
    // Get database connection
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // Prepare SQL statement
    $sql = "DELETE FROM `cart` WHERE userId = ?";

    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $userId); // Assuming userId is an integer, change "i" if it's another type

        // Execute the statement
        if ($stmt->execute()) {
            // Close the statement
            $stmt->close();
            // Return true indicating success
            return true;
        } else {
            // Close the statement
            $stmt->close();
            // Return false indicating failure
            return false;
        }
    } else {
        // Statement preparation failed
        echo "Error preparing statement: " . $mysqli->error;
        // Return false indicating failure
        return false;
    }
}
