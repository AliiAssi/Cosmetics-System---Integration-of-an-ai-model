<?php
function getCategoriesForAdmin() {
    // Get the database connection from the singleton
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // SQL query to fetch categories
    $sql = "SELECT `_id`, `name`, `createdAt`, `available` FROM `productcategory`";
    
    // Array to hold the category objects
    $categories = [];

    try {           
        $result = $mysqli->query($sql);

        // Check if there are any rows returned
        if ($result->num_rows == 0) {
            // Return an empty array if the cart is empty
            return [];
        }

        // Fetch all results at once
        
       while($row = $result->fetch_assoc()) {
            $categories[] = new Category($row['_id'], $row['name'], $row['createdAt'], $row['available']);
        }
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }

    // Return the list of category objects
    return $categories;
}
