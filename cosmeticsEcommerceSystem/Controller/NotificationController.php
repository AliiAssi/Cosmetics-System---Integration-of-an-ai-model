<?php

function getUserNotifications($userId) {
    // Get database connection instance
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();

    // Define the SQL query
    $sql = "
        SELECT 
            notification._id AS notificationId, 
            notification.userId, 
            notification.productId, 
            notification.content, 
            notification.status,
            notification.createdAt,
            product._id AS productId,
            product.name AS productName,
            product.picture AS productPicture,
            product.description AS productDescription
        FROM 
            `notification`
        JOIN 
            `product` ON notification.productId = product._id
        WHERE 
            notification.userId = $userId AND 
            notification.status = 'unseen'
    ";

    // Prepare the statement
    if ($stmt = $dbConnection->prepare($sql)) {
        // Bind the userId parameter
        
        // Execute the statement
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        // Array to store notifications
        $notifications = [];
        
        // Fetch all rows and create notification objects
        while ($row = $result->fetch_assoc()) {
            // Create a product object
            $product = new Product(
                $row['productId'], 
                $row['productName'], 
                null, null, null, 
                $row['productPicture'], 
                $row['productDescription']
            );
            
            // Create a notification object
            $notification = new Notification(
                $row['notificationId'], 
                null,
                $product,
                $row['content'], 
                $row['status'] ,
                $row['createdAt']
            );
            
            // Add the notification object to the array
            $notifications[] = $notification;
        }
        
        // Close the statement
        $stmt->close();
        
        // Return the array of notifications
        return $notifications;
    } else {
        // Handle statement preparation error
        echo "Error preparing statement: " . $dbConnection->error;
        return [];
    }
}

function getUserOrdersNotifications($userId) {
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    
    $sql = "SELECT 
                user_order_notify._id AS notificationId,
                user_order_notify.userId,
                user_order_notify.orderId,
                user_order_notify.content AS content,
                user_order_notify.createdAt AS createdAt,
                `user`._id AS userId,
                `order`._id AS orderId,
                `order`.createdAt AS orderDate,
                `order`.totale AS orderTotal
            FROM 
                `user_order_notify`
            JOIN 
                `user` ON user_order_notify.userId = `user`._id
            JOIN 
                `order` ON user_order_notify.orderId = `order`._id
            WHERE 
                user_order_notify.userId = ? 
                AND user_order_notify.status = 'unseen'";
    
    if ($stmt = $dbConnection->prepare($sql)) {
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $notifications = [];

            while ($row = $result->fetch_assoc()) {
                $notifications[] = $row;
            }

            $stmt->close();
            return $notifications; // Successfully fetched notifications
        } else {
            $stmt->close();
            return false; // Failed to execute
        }
    } else {
        return false; // Failed to prepare the statement
    }
}
function markAsSeen($userId){
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = "UPDATE `notification` SET `status` = 'seen' WHERE `userId` = ? AND `status` = 'unseen'";
    $sql1 = "UPDATE `user_order_notify` SET `status` = 'seen' WHERE `userId` = ? AND `status` = 'unseen'";

    if ($stmt = $dbConnection->prepare($sql)) {
        $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {                                                                                         
            $stmt->close();
            
            // Prepare and execute the second update statement
            if ($stmt1 = $dbConnection->prepare($sql1)) {
                $stmt1->bind_param("i", $userId);
                if ($stmt1->execute()) {
                    $stmt1->close();
                    return true; // Successfully marked as seen in both tables
                } else {
                    $stmt1->close();
                    return false; // Failed to execute second update
                }
            } else {
                return false; // Failed to prepare the second statement
            }
        } else {
            $stmt->close();
            return false; // Failed to execute first update
        }
    } else {
        return false; // Failed to prepare the first statement
    }
}
