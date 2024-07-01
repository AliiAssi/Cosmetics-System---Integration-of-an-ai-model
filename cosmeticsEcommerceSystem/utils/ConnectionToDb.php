<?php
class SingletonConnectionToDb {
    private static $instance; // Hold the class instance.

    private $dbConnection; // Database connection instance.

    // The constructor is private to prevent initiation with outer code.
    private function __construct() {
        // Connect to the database.
        $this->dbConnection = new mysqli('localhost', 'root', '', 'cosmetics');
        
        // Check for connection errors.
        if ($this->dbConnection->connect_error) {
            die("Connection failed: " . $this->dbConnection->connect_error);
        }
    }

    // The object is created from within the class itself only if the class has no instance.
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    // Prevent the instance from being cloned (which would create a second instance of it).
    private function __clone() {}


    // Get the database connection.
    public function getConnection() {
        return $this->dbConnection;
    }
}

