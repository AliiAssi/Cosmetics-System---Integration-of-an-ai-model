<?php 
class ProductCategory {
    private $_id;
    private $name;
    private $createdAt;

    // Constructor
    function __construct($id, $name, $createdAt) {
        $this->_id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
    }

    // Getter methods
    public function getId() {
        return $this->_id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }
}
