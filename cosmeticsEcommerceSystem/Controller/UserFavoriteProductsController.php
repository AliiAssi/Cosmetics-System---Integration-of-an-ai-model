<?php
function getUserFavorites($user_id){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = " SELECT 
        product.*,
        productcategory._id AS categoryId,
        productcategory.name AS categoryName,
        productcategory.createdAt AS categoryCreatedAt
    FROM 
        product
    INNER JOIN 
        productcategory ON product.categoryId = productcategory._id
    INNER JOIN 
        userFavorite ON userFavorite.productId = product._id
    WHERE 
        product.count >= 1
    AND userFavorite.userId = $user_id
    ORDER BY 
        productcategory.createdAt;
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
function isFavorite($user_id, $product_id){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // Prepare the SQL query
    $sql = "SELECT * FROM `userfavorite` WHERE userfavorite.productId = ? AND userfavorite.userId = ?";
    


    // Prepare the statement
    $stmt = $mysqli->prepare($sql);

    // Bind the parameters
    $stmt->bind_param("ii", $product_id, $user_id);

    // Execute the query
    $stmt->execute();

    // Store the result
    $result = $stmt->get_result();

    // Check if any rows are returned
    if ($result->num_rows > 0) {
        // The product is marked as favorite
        return true;
    } else {
        // The product is not marked as favorite
        return false;
    }
}
