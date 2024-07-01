<?php
class User {
    public $_id;
    public $firstName;
    public $lastName;
    public $email;
    public $profilePicture;
    public $userDetails;
    public $isAdmin;
    // Constructor
    public function __construct($_id, $firstName, $lastName, $email, $profilePicture ,$userDetails, $isAdmin) {
        $this->_id = $_id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->profilePicture = $profilePicture;
        $this->userDetails = $userDetails;
        $this->isAdmin = $isAdmin;
    }

    // Getter for $_id
    public function getId() {
        return $this->_id;
    }

    // Setter for $_id
    public function setId($_id) {
        $this->_id = $_id;
    }

    // Getter for $firstName
    public function getFirstName() {
        return $this->firstName;
    }

    // Setter for $firstName
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    // Getter for $lastName
    public function getLastName() { 
        return $this->lastName;
    }

    // Setter for $lastName
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    // Getter for $email
    public function getEmail() {
        return $this->email;
    }

    // Setter for $email
    public function setEmail($email) {
        $this->email = $email;
    }

    // Method to display full name
    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }
    // Method to find the address of the user
    public function getAddress() {
        // Connect to the database using the SingletonConnectionToDb class
        $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    
        // Prepare a query to fetch the user's address by user ID
        $query = "SELECT * FROM `useradressdetails` WHERE userId = ?";
        $statement = $dbConnection->prepare($query);
        $statement->bind_param("i", $this->_id);
    
        // Execute the query
        $statement->execute();
    
        // Get the result
        $result = $statement->get_result();
    
        // Fetch the address
        $address = $result->fetch_assoc();
    
        // Close the statement
        $statement->close();
    
        // Return the address
        return $address;
    }
    public function getStatus(){
        // Connect to the database using the SingletonConnectionToDb class
        $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    
        // Prepare a query to fetch the user's address by user ID
        $query = "SELECT `status` FROM `user` WHERE _id = ?";
        $statement = $dbConnection->prepare($query);
        $statement->bind_param("i", $this->_id);
    
        // Execute the query
        $statement->execute();
    
        // Get the result
        $result = $statement->get_result();
    
        // Fetch the address
        $status = $result->fetch_assoc();

        // Return the address
        return $status['status'];
    }
    public function getAddressAsString(){
        $address = $this->getAddress();
        if ($address)
            return $address['country']."/".$address['city']. "/" . $address['area'];
        else 
            return 'WITHOUT ADDRESS YET';
    }
    
}


