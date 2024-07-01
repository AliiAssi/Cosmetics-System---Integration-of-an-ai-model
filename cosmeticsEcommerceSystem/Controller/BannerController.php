<?php

function getBanners() {
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    
    $banners = []; // Initialize an empty array to store banner objects
    
    $sql = 'SELECT * FROM banner ORDER BY RAND()'; // SQL query to select all banners from the database
    
    // Execute the SQL query
    $result = $dbConnection->query($sql);
    
    // Check if the query was successful
    if ($result) {
        // Fetch banner data and create banner objects
        while ($row = $result->fetch_assoc()) {
            // Create a new banner object using data from the database
            $banner = new Banner($row['_id'], $row['title'], $row['picture']);
            
            // Append the banner object to the array
            $banners[] = $banner;
        }
        
        // Free the result set
        $result->free();
    } else {
        // Handle database query error
        // You can log the error or handle it in any other appropriate way
        // For example, throw an exception
        throw new Exception("Error: " . $dbConnection->error);
    }
    
    // Return the array of banner objects
    return $banners;
}
function countBanners(){
    return count(getBanners());
}
function deleteBanner($id) {
    // Get database connection
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    // Select the previous image path from the database
    $sql = "SELECT picture FROM banner WHERE _id = ?";
    $statement_select = $dbConnection->prepare($sql);
    $statement_select->bind_param("i", $id);
    $statement_select->execute();
    $result = $statement_select->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $previousImagePath = $row['picture'];
        
        // Delete previous image file if it exists
        $filePath = '../View/images/' . $previousImagePath;
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file
        }
    }
    
    // Prepare the DELETE query
    $sql = "DELETE FROM banner WHERE _id = ?";
    
    // Prepare the statement
    $stmt = $dbConnection->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param("i", $id);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo 'deleted';
        return true;
    } else {
        echo 'not';
        // Return false if an error occurred during deletion
        return false;
    }
}
function updateBannerImage($id, $imageName){
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    // Select the previous image path from the database
    $sql = "SELECT picture FROM banner WHERE _id = ?";
    $statement_select = $dbConnection->prepare($sql);
    $statement_select->bind_param("i", $id);
    $statement_select->execute();
    $result = $statement_select->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $previousImagePath = $row['picture'];
        
        // Delete previous image file if it exists
        $filePath = '../View/images/' . $previousImagePath;
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file
        }
    }
    
    // Prepare and execute the SQL update statement to update the image name
    $query = "UPDATE banner SET picture = ? WHERE _id = ?";
    $statement = $dbConnection->prepare($query);
    $statement->bind_param("si", $imageName, $id);    
    // If the update statement executes successfully
    if($statement->execute()){
        // Image updated successfully
        return true;
    } else {
        // Error occurred while updating image
        return false;
    }

}

function updateBannerTitle($id, $title){
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    
    // Assuming you have a table named 'banners' with columns 'id' and 'title'
    $query = "UPDATE banner SET title = ? WHERE _id = ?";
    $statement = $dbConnection->prepare($query);
    $statement->bind_param("si", $title, $id);
    
    // Execute the query
    if($statement->execute()){
        // Title updated successfully
        return true;
    } else {
        // Error occurred while updating title
        return false;
    }
}

function insertBanner($title, $imageName){
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    // Prepare the SQL insert statement
    $query = "INSERT INTO banner (title, picture) VALUES (?, ?)";
    $statement = $dbConnection->prepare($query);
    $statement->bind_param("ss", $title, $imageName);
    
    // Execute the insert statement
    if($statement->execute()){
        // Insertion successful
        return true;
    } else {
        // Insertion failed
        return false;
    }
}
