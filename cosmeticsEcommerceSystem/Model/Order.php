<?php 
class Order {
    public $_id;
    public $orderItems = [];
    public $status;
    public $user;
    public $address;
    public $createdAt;
    public $totale;
    public function __construct($id,$user , $orderItems, $status, $address, $totale , $createdAt){
        $this->_id = $id;
        $this->user = $user;
        $this->orderItems = $orderItems;
        $this->status = $status;
        $this->address = $address;
        $this->totale = $totale;
        $this->createdAt = $createdAt;
    }
}