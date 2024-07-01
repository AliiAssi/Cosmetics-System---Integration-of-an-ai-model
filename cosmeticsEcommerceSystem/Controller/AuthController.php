<?php
include '../utils/ConnectionToDb.php';
include '../utils/autoLoading.php';
function insertUser($firstName, $lastName, $email, $password){
    // Get the database connection
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    
    // Check if user already exists
    $queryCheckExistence = "SELECT _id FROM user WHERE gmail = ?";
    $statementCheckExistence = $mysqli->prepare($queryCheckExistence);
    $statementCheckExistence->bind_param("s", $email);
    $statementCheckExistence->execute();
    $statementCheckExistence->store_result();
    
    // If the user already exists, return false
    if($statementCheckExistence->num_rows > 0) {
        return false; // User already exists
    }
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Write your database insertion query
    $queryInsert = "INSERT INTO user (firstName, lastName, gmail, password) 
                    VALUES (?, ?, ?, ?)";
    
    // Prepare the insertion query
    $statementInsert = $mysqli->prepare($queryInsert);
    
    // Bind parameters for insertion
    $statementInsert->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
    
    // Execute the insertion query
    $result = $statementInsert->execute();
    
    // Close statement resources
    $statementCheckExistence->close();
    $statementInsert->close();
    
    // Return true if insertion was successful, false otherwise
    return true;
}

function loginUser($email, $password) {
    // Connect to the database using the SingletonConnectionToDb class
    $dbConnection = SingletonConnectionToDb::getInstance()->getConnection();

    // Prepare a query to fetch the user by email
    $query = "SELECT * FROM user WHERE gmail = ?";
    $statement = $dbConnection->prepare($query);
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();

    // Check if a user with the provided email exists
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($user['status'] == 1){
            return 'blocked';
        }
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Fetch user details from useradressdetails table based on user ID
            $queryDetails = "SELECT * FROM useradressdetails WHERE userId = ?";
            $statementDetails = $dbConnection->prepare($queryDetails);
            $statementDetails->bind_param("i", $user['_id']); // Assuming userId is an integer
            $statementDetails->execute();
            $resultDetails = $statementDetails->get_result();

            // Check if user details are found
            if ($resultDetails->num_rows == 1) {
                $userDetails = $resultDetails->fetch_assoc();
                // Populate UserDetails object with fetched details
                $userDetailsObject = new UserDetails($userDetails['country'], $userDetails['city'], $userDetails['area']);
            } else {
                // No user details found
                $userDetailsObject = null;
            }

            // Create and return the User object with fetched details
            return new User($user['_id'], $user['firstName'], $user['lastName'], $user['gmail'], $user['profilePicture'], $userDetailsObject,$user['isAdmin']);
        } else {
            // Password is incorrect
            return false;
        }
    } else {
        // User with the provided email does not exist
        return false;
    }
}
