<?php
include '../utils/ConnectionToDb.php';
include '../utils/autoLoading.php';
include '../Controller/CartController.php';
session_start();
/**add to cart route */
if(isset($_GET['product_to_add'])){
    $productId = $_GET['product_to_add'];
    $userId = $_SESSION['user']->_id;
    $result  = addToCart($productId,$userId);
    if($result){
        echo 'ok';
        return ;
    }
    echo 'no';
}
/**delete item route */
if(isset($_GET['item_id'])){
    $cartItem = $_GET['item_id'];
    $userId = $_SESSION['user']->_id;
    $result = deleteItem($cartItem, $userId);
    if($result == true){
        echo countItems($userId);
    }
    else{
        echo 'failed to delete item cz '. $result;
    }
}
/**incremenet qty route */
if(isset($_GET['inc_id']) && isset($_GET['qty'])){
    $cartItem = $_GET['inc_id'];
    $userId = $_SESSION['user']->_id;
    $qty = $_GET['qty'] ;
    $result = updateItemQty($cartItem, $userId, $qty);
    if($result){
        echo 'success';return ;
    }
    echo 'no';
}
/**decrement qty route */
if(isset($_GET['dec_id']) && isset($_GET['qty'])){
    $cartItem = $_GET['dec_id'];
    $userId = $_SESSION['user']->_id;
    $qty = $_GET['qty'] ;
    $result = updateItemQty($cartItem, $userId, $qty);
    if($result){
        echo 'success';return ;
    }
    echo 'error';
}