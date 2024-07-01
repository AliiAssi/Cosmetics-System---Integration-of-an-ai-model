<?php
class OrderItem {
    public $_id;
    public $product;
    public $qty;
    public $productPrice;
    public function __construct($id, $product, $qty, $productPrice){
        $this->_id = $id;
        $this->product = $product;
        $this->qty = $qty;
        $this->productPrice = $productPrice;
    }
}