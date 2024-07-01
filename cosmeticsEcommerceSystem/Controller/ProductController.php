<?php
function getProducts(){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = "
    SELECT product.*,
    productcategory._id AS categoryId , productcategory.name AS categoryName , productcategory.createdAt as categoryCreatedAt
    FROM product
    INNER JOIN productcategory ON product.categoryId = productcategory._id
    WHERE product.count >= 1
    ORDER BY productcategory.createdAt
    ";
    
    // Execute the SQL query
    $result = $mysqli->query($sql);

    // Check if the query was successful
    if ($result) {
        $products = array(); // Initialize an array to store products

        // Fetch associative array of products
        while ($row = $result->fetch_assoc()) {
            // Check if the category already exists in the products array
            if (isset($products[$row['categoryName']])) {
                // Append the product to the existing category
                $products[$row['categoryName']][] = new Product(
                    $row['_id'],
                    $row['name'],
                    $row['price'],
                    $row['discount'],
                    new ProductCategory($row['categoryId'], $row['categoryName'], $row['categoryCreatedAt']),
                    $row['picture'],
                    $row['description']
                );
            } else {
                // Create a new category and add the product to it
                $products[$row['categoryName']] = array(
                    new Product(
                        $row['_id'],
                        $row['name'],
                        $row['price'],
                        $row['discount'],
                        new ProductCategory($row['categoryId'], $row['categoryName'], $row['categoryCreatedAt']),
                        $row['picture'],
                        $row['description']
                    )
                );
            }
        }

        // Return the products array
        return $products;
    } else {
        return [];
    }
}
function getAllProductsForAdmin(){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = "
    SELECT product.*,
    productcategory._id AS categoryId , productcategory.name AS categoryName , productcategory.createdAt as categoryCreatedAt
    FROM product
    INNER JOIN productcategory ON product.categoryId = productcategory._id
    ORDER BY productcategory.createdAt
    ";

    // Execute the SQL query
    $result = $mysqli->query($sql);

    // Check if the query was successful
    if ($result) {
        $products = array(); // Initialize an array to store products

        // Fetch associative array of products
        while ($row = $result->fetch_assoc()) {
            // Check if the category already exists in the products array
            if (isset($products[$row['categoryName']])) {
                // Append the product to the existing category
                $products[$row['categoryName']][] = new Product(
                    $row['_id'],
                    $row['name'],
                    $row['price'],
                    $row['discount'],
                    new ProductCategory($row['categoryId'], $row['categoryName'], $row['categoryCreatedAt']),
                    $row['picture'],
                    $row['description']
                );
            } else {
                // Create a new category and add the product to it
                $products[$row['categoryName']] = array(
                    new Product(
                        $row['_id'],
                        $row['name'],
                        $row['price'],
                        $row['discount'],
                        new ProductCategory($row['categoryId'], $row['categoryName'], $row['categoryCreatedAt']),
                        $row['picture'],
                        $row['description']
                    )
                );
            }
        }

        // Return the products array
        return $products;
    } else {
        return [];
    }
}

function getProduct($id){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // SQL query to fetch the product by ID along with its category
    $sql = "
    SELECT *
    FROM product
    WHERE product._id = ?
    ";

    // Prepare and execute the SQL query
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $id); // Bind the product ID parameter
        $stmt->execute();

        // Fetch the result
        $result = $stmt->get_result();

        // Check if a product was found
        if ($row = $result->fetch_assoc()) {
            
            // Create and return the Product object
            return new Product($row['_id'], $row['name'], $row['price'], $row['discount'], $row['categoryId'], $row['picture'], $row['description']);
        } else {
            // No product found
            return null;
        }
    } else {
        // Query preparation failed
        return null;
    }
}

/**
 * part 2
*/
function deleteProduct($id){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    
    // Start transaction
    $mysqli->begin_transaction();

    try {
        // Step 1: Fetch product details
        $fetchProductSql = "SELECT `_id`, `name`, `price`, `discount`, `categoryId`, `createdAt`, `picture`, `description`, `count` 
                            FROM `product` WHERE `_id` = ?";
        $fetchProductStmt = $mysqli->prepare($fetchProductSql);
        $fetchProductStmt->bind_param("i", $id);
        $fetchProductStmt->execute();
        $productResult = $fetchProductStmt->get_result();

        if ($productResult->num_rows === 0) {
            throw new Exception("Product with ID $id not found.");
        }

        $product = $productResult->fetch_assoc();
        $count = $product['count'];

        // Step 2: Delete product
        $deleteProductSql = "DELETE FROM `product` WHERE `_id` = ?";
        $deleteProductStmt = $mysqli->prepare($deleteProductSql);
        $deleteProductStmt->bind_param("i", $id);
        $deleteProductStmt->execute();

        // Check if product was successfully deleted
        if ($deleteProductStmt->affected_rows === 0) {
            throw new Exception("Failed to delete product with ID $id.");
        }

        // Step 3: Check if count is zero and perform further actions if needed
        if ($count === 0) {
            $mysqli->commit();
            return "Product deleted successfully.";
        }

        // Step 4: Check if product is referenced in order items
        $checkOrderItemSql = "SELECT `_id` FROM `orderitem` WHERE `productId` = ?";
        $checkOrderItemStmt = $mysqli->prepare($checkOrderItemSql);
        $checkOrderItemStmt->bind_param("i", $id);
        $checkOrderItemStmt->execute();
        $orderItemResult = $checkOrderItemStmt->get_result();

        // If no order items reference this product, commit transaction
        if ($orderItemResult->num_rows === 0) {
            $mysqli->commit();
            return "Product deleted successfully.";
        }

        // Step 5: If product is referenced in order items, update product count to zero
        $updateProductCountSql = "UPDATE `product` SET `count` = 0 WHERE `_id` = ?";
        $updateProductCountStmt = $mysqli->prepare($updateProductCountSql);
        $updateProductCountStmt->bind_param("i", $id);
        $updateProductCountStmt->execute();

        // Check if update was successful
        if ($updateProductCountStmt->affected_rows === 0) {
            throw new Exception("Failed to update product count for ID $id.");
        }

        // Commit transaction
        $mysqli->commit();
        return "Product deleted successfully.";
        
    } catch (Exception $e) {
        // Rollback transaction on failure
        $mysqli->rollback();
        return "Transaction failed: " . $e->getMessage();
    } finally {
        // Close prepared statements
        if (isset($fetchProductStmt)) {
            $fetchProductStmt->close();
        }
        if (isset($deleteProductStmt)) {
            $deleteProductStmt->close();
        }
        if (isset($checkOrderItemStmt)) {
            $checkOrderItemStmt->close();
        }
        if (isset($updateProductCountStmt)) {
            $updateProductCountStmt->close();
        }
    }
}

function editProduct($id, $name, $desc, $price, $discount, $qty, $categoryId, $image) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    // Start transaction
    $mysqli->begin_transaction();

    try {
        // Step 1: Fetch previous quantity and discount
        $fetchPrevDetailsSql = "SELECT `count`, `discount` FROM `product` WHERE `_id` = ?";
        $fetchPrevDetailsStmt = $mysqli->prepare($fetchPrevDetailsSql);
        $fetchPrevDetailsStmt->bind_param("i", $id);
        $fetchPrevDetailsStmt->execute();
        $prevDetailsResult = $fetchPrevDetailsStmt->get_result();

        if ($prevDetailsResult->num_rows === 0) {
            throw new Exception("Product with ID $id not found.");
        }

        $prevDetails = $prevDetailsResult->fetch_assoc();
        $prevQty = $prevDetails['count'];
        $prevDiscount = $prevDetails['discount'];

        // Step 2: Update product details
        if ($image === null) {
            $updateProductSql = "UPDATE `product` SET `name`=?, `price`=?, `discount`=?, `categoryId`=?, `description`=?, `count`=? WHERE `_id`=?";
            $updateProductStmt = $mysqli->prepare($updateProductSql);
            $updateProductStmt->bind_param("siiisii", $name, $price, $discount, $categoryId, $desc, $qty, $id);
        } else {
            $updateProductSql = "UPDATE `product` SET `name`=?, `price`=?, `discount`=?, `categoryId`=?, `picture`=?, `description`=?, `count`=? WHERE `_id`=?";
            $updateProductStmt = $mysqli->prepare($updateProductSql);
            $updateProductStmt->bind_param("siiissii", $name, $price, $discount, $categoryId, $image, $desc, $qty, $id);
        }

        $updateProductStmt->execute();

        // Step 3: Check if discount or quantity has increased
        if ($discount > $prevDiscount || $qty > $prevQty) {
            // Step 4: Retrieve all user IDs
            $getUsersSql = "SELECT _id FROM `user`";
            $getUsersStmt = $mysqli->prepare($getUsersSql);
            $getUsersStmt->execute();
            $result = $getUsersStmt->get_result();
            $users = [];

            while ($row = $result->fetch_assoc()) {
                $users[] = $row['_id'];
            }

            $getUsersStmt->close();

            // Step 5: Insert notification for each user
            $notificationContent = "Product details updated. Please review the changes.";
            $insertNotificationSql = "INSERT INTO `notification` (`userId`, `productId`, `content`) VALUES (?, ?, ?)";
            $insertNotificationStmt = $mysqli->prepare($insertNotificationSql);

            foreach ($users as $userId) {
                $insertNotificationStmt->bind_param("iis", $userId, $id, $notificationContent);
                $insertNotificationStmt->execute();
            }

            $insertNotificationStmt->close();
        }

        // Commit transaction
        $mysqli->commit();
        return "Product updated successfully.";

    } catch (Exception $e) {
        // Rollback transaction on failure
        $mysqli->rollback();
        return "Transaction failed: " . $e->getMessage();
    } finally {
        // Close prepared statements
        $fetchPrevDetailsStmt->close();
        if (isset($updateProductStmt)) {
            $updateProductStmt->close();
        }
    }
}


function insertProduct($name, $description, $price, $discount, $qty, $categoryId, $image) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    
    // Sanitize input data to prevent SQL injection
    $name = mysqli_real_escape_string($mysqli, $name);
    $description = mysqli_real_escape_string($mysqli, $description);
    $price = (float) $price; // Assuming price is a float value
    $discount = (float) $discount; // Assuming discount is a float value
    $qty = (int) $qty; // Assuming qty is an integer value
    $categoryId = (int) $categoryId; // Assuming categoryId is an integer value
    $image = mysqli_real_escape_string($mysqli, $image); // Assuming image is a string (file path)
    
    // Construct the SQL query
    $sql = "INSERT INTO `product` (`name`, `price`, `discount`, `categoryId`, `picture`, `description`, `count`) 
            VALUES ('$name', $price, $discount, $categoryId, '$image', '$description', $qty)";

    // Execute the query
    if ($mysqli->query($sql) === TRUE) {
        return "Product inserted successfully.";
    } else {
        return "Error: " . $sql . "<br>" . $mysqli->error;
    }
}



