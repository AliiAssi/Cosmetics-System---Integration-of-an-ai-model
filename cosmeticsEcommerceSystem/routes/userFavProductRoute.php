<?php
include '../utils/ConnectionToDb.php';

// Establish database connection
$mysqli = SingletonConnectionToDb::getInstance()->getConnection();

// Check if 'pId' parameter is set in the URL
if(isset($_GET['pId']) && isset($_GET['uId'])) {
    $productId = $_GET['pId'];
    $userId = $_GET['uId']; // Corrected variable name
    $sql_check = "SELECT * FROM `userfavorite` WHERE `productId` = ? AND `userId` = ?";
    $stmt_check = $mysqli->prepare($sql_check);
    $stmt_check->bind_param("ii", $productId, $userId);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        // Record already exists, so delete it
        $sql_delete = "DELETE FROM `userfavorite` WHERE `productId` = ? AND `userId` = ?";
        $stmt_delete = $mysqli->prepare($sql_delete);
        $stmt_delete->bind_param("ii", $productId, $userId);
        $stmt_delete->execute();
        
        if ($stmt_delete->affected_rows > 0) {
            echo "Product removed from favorites successfully.";
        } else {
            echo "Failed to remove product from favorites.";
        }
    } else {
        // Record doesn't exist, so insert it
        $sql_insert = "INSERT INTO `userfavorite`(`productId`, `userId`) VALUES (?, ?)";
        $stmt_insert = $mysqli->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $productId, $userId);
        $stmt_insert->execute();
        
        if ($stmt_insert->affected_rows > 0) {
            echo "Product added to favorites successfully.";
        } else {
            echo "Failed to add product to favorites.";
        }
    }
} else {
    // Error handling for missing 'pId' parameter
    echo "Error: 'pId' parameter is missing.";
}
