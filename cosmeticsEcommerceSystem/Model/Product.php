<?php
class Product {
    private $_id;
    private $name;
    private $price;
    private $discount;
    private $category;
    private $picture;
    private $description;

    // Constructor
    function __construct($_id, $name, $price, $discount, $category, $picture, $description) {
        $this->_id = $_id;
        $this->name = $name;
        $this->price = $price;
        $this->discount = $discount;
        $this->category = $category;
        $this->picture = $picture;
        $this->description = $description;
    }

    // Getter methods
    public function getId() {
        return $this->_id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getPicture() {
        return $this->picture;
    }

    public function getDescription() {
        return $this->description;
    }
    public function getProductCount() {
        $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
        $sql = "SELECT count FROM product WHERE _id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $this->_id); // Assuming product_id is an integer
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['count'];
        } else {
            // Handle error if query fails
            return false;
        }
    }
    
}

