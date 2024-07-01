<?php
class Category {
    public $_id;
    public $name;
    public $createdAt;
    public $available;
    public function __construct($_id, $name, $createdAt, $available) {
        $this->_id = $_id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->available = $available;
    }
}