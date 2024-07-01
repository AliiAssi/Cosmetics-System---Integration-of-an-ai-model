<?php
class Cart{
    public $userId;
    public $product;
    public $qty;
    public function __construct($userId, $product, $qty){
        $this->userId = $userId;
        $this->product = $product;
        $this->qty = $qty;
    }
}