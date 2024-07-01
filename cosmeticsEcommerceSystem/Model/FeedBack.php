<?php
class FeedBack{
    public $_id;
    public $content;
    public $user_id;
    public $howMuch;
    public $acceptance;
    public $homeDisplayed;
    
    public function __construct($_id , $content, $howMuch,$acceptance, $homeDisplayed , $user_id){
        $this->_id = $_id;
        $this->content = $content;
        $this->user_id = $user_id;
        $this->howMuch = $howMuch;
        $this->acceptance = $acceptance;
        $this->homeDisplayed = $homeDisplayed;
    }

    public function getFeedBackOwner(){
        // Connect to the database using the SingletonConnectionToDb class
        $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();
    
        // Prepare a query to fetch the user by _id
        $query = "SELECT * 
        FROM user 
        LEFT JOIN useradressdetails ON user._id = useradressdetails.userId 
        WHERE user._id = ?;
        ";
        $statement = $dbConnection->prepare($query);
        $statement->bind_param("i", $this->user_id);
        $statement->execute();
        $result = $statement->get_result();
    
        // Fetch user information
        $user = null;
        if ($row = $result->fetch_assoc()) {
            $userDetails = new UserDetails($row['country'], $row['city'], $row['area']);
            
            $user = new User($row['_id'], $row['firstName'], $row['lastName'], $row['gmail'], $row['profilePicture'],$userDetails, $row['isAdmin']);
        }
    
        // Free result and close statement
        $statement->close();
    
        // Return user information
        return $user;
    }
}

