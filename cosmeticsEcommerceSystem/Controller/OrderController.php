<?php
function handleCartOrder($cartItems) 
{
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $userId = $_SESSION['user']->_id;
    $address = $_SESSION['user']->userDetails->country . "/" . $_SESSION['user']->userDetails->city . "/" . $_SESSION['user']->userDetails->area;

    // Start a transaction
    $mysqli->begin_transaction();

    try {
        $total = 0;
        // Calculate total order amount
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            $discountedPrice = $product->getPrice() * (1 - $product->getDiscount() / 100);
            $total += $discountedPrice * $cartItem->qty;
        }

        // Insert order details
        $sqlOrder = "INSERT INTO `order` (`totale`, `userId`, `address`) VALUES (?, ?, ?)";
        $stmtOrder = $mysqli->prepare($sqlOrder);
        $stmtOrder->bind_param("dss", $total, $userId, $address);
        $stmtOrder->execute();

        // Get the last inserted order ID
        $orderId = $mysqli->insert_id;

        // Check and update product quantities
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            $productId = $product->getId();
            $requestedQty = $cartItem->qty;

            // Check if enough quantity available
            $sql_check = "SELECT `count` FROM `product` WHERE `_id` = ?";
            $stmt_check = $mysqli->prepare($sql_check);
            $stmt_check->bind_param("i", $productId);
            $stmt_check->execute();
            $stmt_check->bind_result($currentQty);
            $stmt_check->fetch();
            $stmt_check->close();

            if ($currentQty < $requestedQty) {
                throw new Exception("Insufficient quantity for product: " . $product->getName());
            }

            // Update product quantity
            $newQty = $currentQty - $requestedQty;
            $sql_update_qty = "UPDATE `product` SET `count` = ? WHERE `_id` = ?";
            $stmt_update_qty = $mysqli->prepare($sql_update_qty);
            $stmt_update_qty->bind_param("ii", $newQty, $productId);
            $stmt_update_qty->execute();
            $stmt_update_qty->close();

            // Insert order item
            $productPriceAfterDiscount = $product->getPrice() * (1 - $product->getDiscount() / 100);
            $sqlOrderItem = "INSERT INTO `orderitem` (`productId`, `price`, `orderId`, `qty`) VALUES (?, ?, ?, ?)";
            $stmtOrderItem = $mysqli->prepare($sqlOrderItem);
            $stmtOrderItem->bind_param("idii", $productId, $productPriceAfterDiscount, $orderId, $requestedQty);
            $stmtOrderItem->execute();
            $stmtOrderItem->close();
        }

        // Commit the transaction
        $mysqli->commit();

        // Return true if everything executed successfully
        return true;
    } catch (mysqli_sql_exception $e) {
        // Rollback the transaction if a MySQL error occurred
        $mysqli->rollback();
        // Return false indicating failure
        return "MySQL Error: " . $e->getMessage();
    } catch (Exception $e) {
        // Rollback the transaction if any other error occurred
        $mysqli->rollback();
        // Return false indicating failure
        return "Error: " . $e->getMessage();
    }
}

function getLastOrder($userId)
{
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = "SELECT * FROM `order` WHERE userId = ? ORDER BY createdAt DESC LIMIT 1";

    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $userId); // Assuming userId is an integer, change "i" if it's another type

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the row
        if ($row = $result->fetch_assoc()) {
            $orderId = $row['_id'];

            // Fetch associated order items
            $sqlOrderItems = "SELECT * FROM `orderitem` WHERE orderId = ?";
            $stmtOrderItems = $mysqli->prepare($sqlOrderItems);
            $stmtOrderItems->bind_param("i", $orderId);
            $stmtOrderItems->execute();
            $resultOrderItems = $stmtOrderItems->get_result();

            // Fetch all order items
            $orderItems = [];
            while ($rowOrderItem = $resultOrderItems->fetch_assoc()) {
                // Fetch product details for each order item
                $productId = $rowOrderItem['productId'];
                $sqlProduct = "SELECT * FROM `product` WHERE _id = ?";
                $stmtProduct = $mysqli->prepare($sqlProduct);
                $stmtProduct->bind_param("i", $productId);
                $stmtProduct->execute();
                $resultProduct = $stmtProduct->get_result();
                $productData = $resultProduct->fetch_assoc();

                // Create Product object
                $product = new Product($productData['_id'], $productData['name'], null, null, null, $productData['picture'], $productData['description']);

                // Create OrderItem object
                $orderItems[] = new OrderItem($productId, $product, $rowOrderItem['qty'], $rowOrderItem['price']);

                // Close product statement
                $stmtProduct->close();
            }

            // Close the statement for fetching order items
            $stmtOrderItems->close();

            // Return the order details
            return new Order($orderId,null, $orderItems, $row['status'], $row['address'], $row['totale'], $row['createdAt']);
        } else {
            // No order found
            return null;
        }
    } else {
        // Statement preparation failed
        echo "Error preparing statement: " . $mysqli->error;
        // Return null indicating failure
        return null;
    }
}
function getOrder($userId, $orderId)
{
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = "SELECT * FROM `order` WHERE userId = ? AND _id = ?";

    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("ii", $userId, $orderId); // Assuming userId is an integer, change "i" if it's another type

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the row
        if ($row = $result->fetch_assoc()) {
            $orderId = $row['_id'];

            // Fetch associated order items
            $sqlOrderItems = "SELECT * FROM `orderitem` WHERE orderId = ?";
            $stmtOrderItems = $mysqli->prepare($sqlOrderItems);
            $stmtOrderItems->bind_param("i", $orderId);
            $stmtOrderItems->execute();
            $resultOrderItems = $stmtOrderItems->get_result();

            // Fetch all order items
            $orderItems = [];
            while ($rowOrderItem = $resultOrderItems->fetch_assoc()) {
                // Fetch product details for each order item
                $productId = $rowOrderItem['productId'];
                $sqlProduct = "SELECT * FROM `product` WHERE _id = ?";
                $stmtProduct = $mysqli->prepare($sqlProduct);
                $stmtProduct->bind_param("i", $productId);
                $stmtProduct->execute();
                $resultProduct = $stmtProduct->get_result();
                $productData = $resultProduct->fetch_assoc();

                // Create Product object
                $product = new Product($productData['_id'], $productData['name'], null, null, null, $productData['picture'], $productData['description']);

                // Create OrderItem object
                $orderItems[] = new OrderItem($productId, $product, $rowOrderItem['qty'], $rowOrderItem['price']);

                // Close product statement
                $stmtProduct->close();
            }

            // Close the statement for fetching order items
            $stmtOrderItems->close();

            // Return the order details
            return new Order($orderId,null, $orderItems, $row['status'], $row['address'], $row['totale'], $row['createdAt']);
        } else {
            // No order found
            return null;
        }
    } else {
        // Statement preparation failed
        echo "Error preparing statement: " . $mysqli->error;
        // Return null indicating failure
        return null;
    }
}

function getOrders($userId)
{
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = "SELECT * FROM `order` WHERE userId = ? ORDER BY createdAt DESC";

    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $userId); // Assuming userId is an integer, change "i" if it's another type

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Array to store orders
        $orders = [];

        // Fetch all rows
        while ($row = $result->fetch_assoc()) {
            $orderId = $row['_id'];

            // Fetch associated order items
            $sqlOrderItems = "SELECT * FROM `orderitem` WHERE orderId = ?";
            $stmtOrderItems = $mysqli->prepare($sqlOrderItems);
            $stmtOrderItems->bind_param("i", $orderId);
            $stmtOrderItems->execute();
            $resultOrderItems = $stmtOrderItems->get_result();

            // Fetch all order items
            $orderItems = [];
            while ($rowOrderItem = $resultOrderItems->fetch_assoc()) {
                // Fetch product details for each order item
                $productId = $rowOrderItem['productId'];
                $sqlProduct = "SELECT * FROM `product` WHERE _id = ?";
                $stmtProduct = $mysqli->prepare($sqlProduct);
                $stmtProduct->bind_param("i", $productId);
                $stmtProduct->execute();
                $resultProduct = $stmtProduct->get_result();
                $productData = $resultProduct->fetch_assoc();

                // Create Product object
                $product = new Product($productData['_id'], $productData['name'], null, null, null, $productData['picture'], $productData['description']);

                // Create OrderItem object
                $orderItems[] = new OrderItem($productId, $product, $rowOrderItem['qty'], $rowOrderItem['price']);

                // Close product statement
                $stmtProduct->close();
            }

            // Close the statement for fetching order items
            $stmtOrderItems->close();

            // Add the order to the orders array
            $orders[] = new Order($orderId,null, $orderItems, $row['status'], $row['address'], $row['totale'], $row['createdAt']);
        }

        // Close the statement for fetching orders
        $stmt->close();

        // Return the array of orders
        return $orders;
    } else {
        // Statement preparation failed
        echo "Error preparing statement: " . $mysqli->error;
        // Return an empty array indicating failure
        return [];
    }
}

function countUserOrders($userId) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = "SELECT COUNT(*) AS orderCount FROM `order` WHERE userId = ?";

    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $userId); // Assuming userId is an integer, change "i" if it's another type

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the row
        if ($row = $result->fetch_assoc()) {
            // Retrieve and return the order count
            return $row['orderCount'];
        } else {
            // No orders found, return 0
            return 0;
        }
    } else {
        // Statement preparation failed
        echo "Error preparing statement: " . $mysqli->error;
        // Return -1 indicating failure
        return -1;
    }
}

function countOrders($status) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    $sql = "SELECT COUNT(*) as orderCount FROM `order` WHERE `status` = ?";

    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("s", $status);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Fetch the order count
        $orderCount = $row['orderCount'];

        // Close the statement
        $stmt->close();

        // Return the order count
        return $orderCount;
    } else {
        // Statement preparation failed
        echo "Error preparing statement: " . $mysqli->error;
        // Return 0 indicating failure
        return 0;
    }
}

function getOrdersByStatus($status, $maxOrdersToFetch, $currentPage) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // Calculate the offset
    $offset = ($currentPage - 1) * $maxOrdersToFetch;

    // Main SQL query with limit and offset for pagination
    $sql = "SELECT `order`._id as orderId,
            `user`._id as userId,
            `user`.`firstName` as userFnName,
            `user`.`lastName` as userLnName,
            `user`.`gmail` as userEmail, 
            `user`.`profilePicture` as userProfilePicture, 
            `order`.`status`, `order`.`address`, `order`.`totale`, `order`.`createdAt`
            FROM `order`
            JOIN `user` ON `order`.`userId` = `user`._id
            WHERE `order`.`status` = ?
            LIMIT ? OFFSET ?";

    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sii", $status, $maxOrdersToFetch, $offset);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Array to store orders
        $orders = [];

        // Fetch all rows
        while ($row = $result->fetch_assoc()) {
            $orderId = $row['orderId'];
            $user = new User($row['userId'], $row['userFnName'], $row['userLnName'],$row['userEmail'],$row['userProfilePicture'],null,null); // Assuming a User class constructor

            // Fetch associated order items
            $sqlOrderItems = "SELECT * FROM `orderitem` WHERE orderId = ?";
            $stmtOrderItems = $mysqli->prepare($sqlOrderItems);
            $stmtOrderItems->bind_param("i", $orderId);
            $stmtOrderItems->execute();
            $resultOrderItems = $stmtOrderItems->get_result();

            // Fetch all order items
            $orderItems = [];
            while ($rowOrderItem = $resultOrderItems->fetch_assoc()) {
                // Fetch product details for each order item
                $productId = $rowOrderItem['productId'];
                $sqlProduct = "SELECT * FROM `product` WHERE _id = ?";
                $stmtProduct = $mysqli->prepare($sqlProduct);
                $stmtProduct->bind_param("i", $productId);
                $stmtProduct->execute();
                $resultProduct = $stmtProduct->get_result();
                $productData = $resultProduct->fetch_assoc();

                // Create Product object
                $product = new Product($productData['_id'], $productData['name'], null, null, null, $productData['picture'], $productData['description']);

                // Create OrderItem object
                $orderItems[] = new OrderItem($productId, $product, $rowOrderItem['qty'], $rowOrderItem['price']);

                // Close product statement
                $stmtProduct->close();
            }

            // Close the statement for fetching order items
            $stmtOrderItems->close();

            // Add the order to the orders array
            $orders[] = new Order($orderId, $user, $orderItems, $row['status'], $row['address'], $row['totale'], $row['createdAt']);
        }

        // Close the statement for fetching orders
        $stmt->close();

        // Return the array of orders
        return $orders;
    } else {
        // Statement preparation failed
        echo "Error preparing statement: " . $mysqli->error;
        // Return an empty array indicating failure
        return [];
    }
}


/******status order */
function rejectOrder($id){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    
    // Start transaction
    $mysqli->begin_transaction();

    try {
        // Step 1: Fetch order details and status
        $sql = "SELECT `_id`, `status` , `userId` FROM `order` WHERE _id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Order with ID $id not found.");
        }

        $order = $result->fetch_assoc();
        $userId = $order['userId'];

        // Step 2: Update order status to 'rejected'
        $updateStatusSql = "UPDATE `order` SET `status` = 'canceled' WHERE `_id` = ?";
        $updateStatusStmt = $mysqli->prepare($updateStatusSql);
        $updateStatusStmt->bind_param("i", $id);
        $updateStatusStmt->execute();

        // Step 3: Adjust product counts in order items
        $orderItemsSql = "SELECT `_id`, `productId`, `qty` FROM `orderitem` WHERE `orderId` = ?";
        $orderItemsStmt = $mysqli->prepare($orderItemsSql);
        $orderItemsStmt->bind_param("i", $id);
        $orderItemsStmt->execute();
        $orderItemsResult = $orderItemsStmt->get_result();

        while ($orderItem = $orderItemsResult->fetch_assoc()) {
            $productId = $orderItem['productId'];
            $qty = $orderItem['qty'];

            // Update product count
            $updateProductSql = "UPDATE `product` SET `count` = `count` + ? WHERE `_id` = ?";
            $updateProductStmt = $mysqli->prepare($updateProductSql);
            $updateProductStmt->bind_param("ii", $qty, $productId);
            $updateProductStmt->execute();
        }

        // Commit transaction
        $mysqli->commit();

        // Step 4: Send notification to the customer
        $notificationSql = "INSERT INTO `user_order_notify` (`userId`, `orderId`, `content`, `createdAt`, `status`) 
                            VALUES (?, ?, ?, NOW(), 'unseen')";
        // Example content for rejection notification
        $notificationContent = "Your order (ID: $id) has been rejected.";

        $notificationStmt = $mysqli->prepare($notificationSql);
        $notificationStmt->bind_param("iss", $userId, $id, $notificationContent);
        // Assuming you have userId and $id defined somewhere in your code

        if ($notificationStmt->execute()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        // Rollback transaction on failure
        return false;
    }
}

function updateOrderStatus($id,$status){ //completed
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    
    // Start transaction
    $mysqli->begin_transaction();

    try {
        // Step 1: Fetch order details and status
        $sql = "SELECT `_id`, `status` , `userId` FROM `order` WHERE _id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Order with ID $id not found.");
        }

        $order = $result->fetch_assoc();
        $userId = $order['userId'];
        // Step 1: Update order status to 'accepted'
        $updateStatusSql = "UPDATE `order` SET `status` = '$status' WHERE `_id` = ?";
        $updateStatusStmt = $mysqli->prepare($updateStatusSql);
        $updateStatusStmt->bind_param("i", $id);
        $updateStatusStmt->execute();

        // Step 2: Send notification to the customer
        $notificationSql = "INSERT INTO `user_order_notify` (`userId`, `orderId`, `content`, `createdAt`, `status`) 
                            VALUES (?, ?, ?, NOW(), 'unseen')";
        // Example content for acceptance notification
        $notificationContent = "Your order (ID: $id) has been $status.";

        $notificationStmt = $mysqli->prepare($notificationSql);
        $notificationStmt->bind_param("iss", $userId, $id, $notificationContent);
        // Assuming you have userId and $id defined somewhere in your code

        if ($notificationStmt->execute()) {
            echo "Order (ID: $id) accepted successfully.";
        } else {
            throw new Exception("Failed to send notification.");
        }

        // Commit transaction
        $mysqli->commit();
        return true;
    } catch (Exception $e) {
        return false;
    } 
}

/** cancel order from the user */
function cancelOrderById($orderId) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // Check the status of the order
    $sqlStatus = "SELECT status FROM `order` WHERE _id = ?";
    if ($stmtStatus = $mysqli->prepare($sqlStatus)) {
        $stmtStatus->bind_param("i", $orderId);
        $stmtStatus->execute();
        $resultStatus = $stmtStatus->get_result();

        if ($rowStatus = $resultStatus->fetch_assoc()) {
            $status = $rowStatus['status'];
            if ($status === 'placed' || $status === 'canceled') {
                // Begin transaction
                $mysqli->begin_transaction();

                try {
                    // Retrieve order items and their quantities
                    $sqlGetOrderItems = "SELECT productId, qty FROM `orderitem` WHERE orderId = ?";
                    if ($stmtGetOrderItems = $mysqli->prepare($sqlGetOrderItems)) {
                        $stmtGetOrderItems->bind_param("i", $orderId);
                        $stmtGetOrderItems->execute();
                        $resultOrderItems = $stmtGetOrderItems->get_result();

                        // Increment the quantity of each product
                        while ($rowOrderItem = $resultOrderItems->fetch_assoc()) {
                            $productId = $rowOrderItem['productId'];
                            $qty = $rowOrderItem['qty'];

                            $sqlIncrementQty = "UPDATE `product` SET `count` = `count` + ? WHERE `_id` = ?";
                            if ($stmtIncrementQty = $mysqli->prepare($sqlIncrementQty)) {
                                $stmtIncrementQty->bind_param("ii", $qty, $productId);
                                $stmtIncrementQty->execute();
                                $stmtIncrementQty->close();
                            } else {
                                throw new Exception("Error preparing statement: " . $mysqli->error);
                            }
                        }
                        $stmtGetOrderItems->close();
                    } else {
                        throw new Exception("Error preparing statement: " . $mysqli->error);
                    }

                    // Delete order items
                    $sqlDeleteOrderItems = "DELETE FROM `orderitem` WHERE orderId = ?";
                    $stmtDeleteOrderItems = $mysqli->prepare($sqlDeleteOrderItems);
                    $stmtDeleteOrderItems->bind_param("i", $orderId);
                    $stmtDeleteOrderItems->execute();
                    $stmtDeleteOrderItems->close();

                    // Delete the order
                    $sqlDeleteOrder = "DELETE FROM `order` WHERE _id = ?";
                    $stmtDeleteOrder = $mysqli->prepare($sqlDeleteOrder);
                    $stmtDeleteOrder->bind_param("i", $orderId);
                    $stmtDeleteOrder->execute();
                    $stmtDeleteOrder->close();

                    // Commit transaction
                    $mysqli->commit();

                    return true; // Deletion successful
                } catch (Exception $e) {
                    // Rollback transaction if an error occurred
                    $mysqli->rollback();
                    echo "Error: " . $e->getMessage();
                    return false; // Deletion failed
                }
            } else {
                // Order status is not 'placed' or 'canceled', cannot delete
                return false;
            }
        } else {
            // Order not found
            return false;
        }
    } else {
        // Statement preparation failed
        echo "Error preparing statement: " . $mysqli->error;
        return false; // Deletion failed
    }
}
