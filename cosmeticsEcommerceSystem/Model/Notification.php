<?php
class Notification {
    public $_id;
    public $userId;
    public $product;
    public $content;
    public $status;
    public $createdAt;
    public function __construct($_id,$userId, $product, $content, $status, $createdAt){
        $this->_id = $_id; 
        $this->userId = $userId;
        $this->product = $product;
        $this->content = $content;
        $this->status = $status;
        $this->createdAt = $createdAt;
    }
}