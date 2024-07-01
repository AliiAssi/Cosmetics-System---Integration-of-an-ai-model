<?php
include '../utils/autoLoading.php';
include '../utils/ConnectionToDb.php';
include '../Controller/CartController.php';
include '../Controller/OrderController.php';
session_start();
function placeOrder(){
    if(isset($_GET['add']) && $_GET['add']){
        // find user cart items
        $cartItems = getUserCart($_SESSION['user']->_id);
        // handle order items
        $result = handleCartOrder($cartItems);
        if($result != true){
            $_SESSION['error'] = $result;
            header("Location:../View/pages/checkout.php"); 
            exit();
        }else{
            // delete all records of user
            if(!deleteCart($_SESSION['user']->_id)){
                $_SESSION['error'] = "error deleting user cart";
                header("Location:../View/pages/checkout.php"); 
                exit();
            }
        }
        //redirect to history page
        $_SESSION['message'] = " order has been created successfully ";
        header("Location:../View/pages/history.php");
    }
}

function canceleOrder(){
    if(isset($_GET['deleteOrderId'])){
        if(!is_numeric($_GET['deleteOrderId'])){
            echo 'order id not numeric';    exit();
        }
        $count = countUserOrders($_SESSION['user']->_id);
        echo $count;
        cancelOrderById($_GET['deleteOrderId']);
    }
}
canceleOrder();
placeOrder();

/**
 * part 2
 */

if(isset($_GET['orderId'])){
    $orderId = $_GET['orderId'];
    $target  = $_GET['s'];
    $res = false;
    if($target === '0') $res = rejectOrder($orderId);
    if($target === '1') $res = updateOrderStatus($orderId,'accepted');
    if($target === '2') $res = updateOrderStatus($orderId,'completed');
    $_SESSION['message'] = 'Order status updated';
    if(!$res){
        $_SESSION['message'] = 'Error updating order status ';
    }
    header("Location:../View/pages/order.php");
}