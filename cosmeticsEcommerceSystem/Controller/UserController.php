<?php
function changeUserPassword($oldPassword, $newPassword, $userId) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // Retrieve the hashed password of the user from the database
    $query = "SELECT password FROM user WHERE _id = ?";
    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $userId);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $hashedPasswordFromDb = $user['password'];
        echo $hashedPasswordFromDb . "<br>";
        echo password_hash($oldPassword, PASSWORD_DEFAULT);

        // Verify if the old password matches the password stored in the database
        if (password_verify($oldPassword, $hashedPasswordFromDb)) {
            // Hash the new password
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateQuery = "UPDATE user SET password = ? WHERE _id = ?";
            $updateStatement = $mysqli->prepare($updateQuery);
            $updateStatement->bind_param("si", $hashedNewPassword, $userId);
            $updateResult = $updateStatement->execute();

            if ($updateResult) {
                // Password changed successfully
                return true;
            } else {
                // Error updating password
                return false;
            }
        } else {
            // Old password does not match
            return false;
        }
    } else {
        // User not found
        return false;
    }
}
function getUserInfo($userId) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // Prepare a query to fetch user information based on user ID
    $query = "SELECT _id, firstName, lastName, gmail FROM user WHERE _id = ?";
    $statement = $mysqli->prepare($query);
    $statement->bind_param("i", $userId);
    $statement->execute();
    $result = $statement->get_result();

    // Check if a user with the provided user ID exists
    if ($result->num_rows == 1) {
        $userData = $result->fetch_assoc();
        // Fetch user details from useradressdetails table based on user ID
        $queryDetails = "SELECT * FROM useradressdetails WHERE userId = ?";
        $statementDetails = $mysqli->prepare($queryDetails);
        $statementDetails->bind_param("i", $userData['_id']); // Assuming userId is an integer
        $statementDetails->execute();
        $resultDetails = $statementDetails->get_result();        // Create a new User object and populate it with user data
        // Check if user details are found
        if ($resultDetails->num_rows == 1) {
            $userDetails = $resultDetails->fetch_assoc();
            // Populate UserDetails object with fetched details
            $userDetailsObject = new UserDetails($userDetails['country'], $userDetails['city'], $userDetails['area']);
        } else {
            // No user details found
            $userDetailsObject = null;
        }
        $user = new User($userData['_id'], $userData['firstName'], $userData['lastName'], $userData['gmail'],$userData['profilePicture'], $userDetailsObject,$userData['isAdmin']);
        return $user; // Return the User object
    } else {
        // User not found
        return null;
    }
}

function updateUserProfilePicture($userId, $imagePath) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    // Check if the user has an image_path different from 'default.jpg'
    $querySelect = "SELECT profilePicture FROM user WHERE _id = ?";
    $statementSelect = $mysqli->prepare($querySelect);
    $statementSelect->bind_param("i", $userId);
    $statementSelect->execute();
    $result = $statementSelect->get_result();

    if ($result->num_rows == 1) {
        $userData = $result->fetch_assoc();
        $previousImagePath = $userData['profilePicture'];

        //die($previousImagePath);
        // Delete the previous image if it's different from 'default.jpg'
        if ($previousImagePath != 'default.jpg') {
            $filePath = '../View/uploaded_img/' . basename($previousImagePath);
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }
            else{
                die($filePath);
            }
        }

    }

    // Update the user's profile picture path in the database
    $queryUpdate = "UPDATE user SET profilePicture = ? WHERE _id = ?";
    $statementUpdate = $mysqli->prepare($queryUpdate);
    $statementUpdate->bind_param("si", $imagePath, $userId);

    // Execute the update query
    if ($statementUpdate->execute()) {
        // Update the profile picture path in the session
        return true; // Profile picture updated successfully
    } else {
        return false; // Failed to update profile picture
    }
}
function deleteUserProfilePicture($userId){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    // Check if the user has an image_path different from 'default.jpg'
    $querySelect = "SELECT profilePicture FROM user WHERE _id = ?";
    $statementSelect = $mysqli->prepare($querySelect);
    $statementSelect->bind_param("i", $userId);
    $statementSelect->execute();
    $result = $statementSelect->get_result();

    if ($result->num_rows == 1) {
        $userData = $result->fetch_assoc();
        $previousImagePath = $userData['profilePicture'];
        if ($previousImagePath === 'default.jpg') {
            return false;
        }
        $filePath = '../View/uploaded_img/' . basename($previousImagePath);
        if (file_exists($filePath)) {
            unlink($filePath); // Delete the file
        }
    }

    // Update the user's profile picture path in the database
    $queryUpdate = "UPDATE user SET profilePicture = ? WHERE _id = ?";
    $statementUpdate = $mysqli->prepare($queryUpdate);
    $default = "default.jpg";
    $statementUpdate->bind_param("si", $default, $userId);
    // Execute the update query
    if ($statementUpdate->execute()) {
        // Update the profile picture path in the session
        return true; // Profile picture updated successfully
    } else {
        return false; // Failed to update profile picture
    }
}
function updateUserProfileDetails($userId, $firstName, $lastName, $email, $country, $city, $area, $hidden) {
    // Get MySQLi instance from SingletonConnectionToDb
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    if ($mysqli->connect_errno) {
        // Database connection error
        return "Database connection error: " . $mysqli->connect_error;
    }

    if ($hidden == 0) {
        // User already has details, update user address details
        $sql = "UPDATE useradressdetails SET country=?, city=?, area=? WHERE userId=?";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            // Query preparation error
            return "Query preparation error: " . $mysqli->error;
        }
        $stmt->bind_param("sssi", $country, $city, $area, $userId);
        if (!$stmt->execute()) {
            // Query execution error
            return "Query execution error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Insert user details into user address details
        $sql = "INSERT INTO useradressdetails (userId, country, city, area) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            // Query preparation error
            return "Query preparation error: " . $mysqli->error;
        }
        $stmt->bind_param("isss", $userId, $country, $city, $area);
        if (!$stmt->execute()) {
            // Query execution error
            return "Query execution error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Update user table with new profile information
    $sql = "UPDATE user SET firstName=?, lastName=?, gmail=? WHERE _id=?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        // Query preparation error
        return "Query preparation error: " . $mysqli->error;
    }
    $stmt->bind_param("sssi", $firstName, $lastName, $email, $userId);
    if (!$stmt->execute()) {
        // Query execution error
        return "Query execution error: " . $stmt->error;
    }
    $stmt->close();
    $isAdmin = $_SESSION['user']->isAdmin;
    $so = 0;
    if($isAdmin === 1){
        $so = 1;
    }

    $updatedUser = new User($_SESSION['user']->_id, $firstName, $lastName, $email ,$_SESSION['user']->profilePicture, 
    new UserDetails($country, $city, $area),
    $so
    );
    unset($_SESSION['user']);
    $_SESSION['user'] = $updatedUser;
    return true; // Return true indicating successful profile update
}

function getAllUsersForAnAdmin(){
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = "SELECT `_id`, `firstName`, `lastName`, `gmail`, `password`, `isAdmin`, `status`, `createdAt`, `profilePicture` FROM `user` 
    WHERE isAdmin = 0";

    // Initialize an empty array to store users
    $users = [];

    // Execute the SQL query
    $result = $mysqli->query($sql);

    // Check if the query was successful
    if ($result) {
        // Fetch associative array of users
        while ($row = $result->fetch_assoc()) {
            // Create user objects and add them to the $users array
            $users[] = new User(
                $row['_id'],
                $row['firstName'],
                $row['lastName'],
                $row['gmail'],
                $row['profilePicture'],
                null,
                null
            );
        }

        // Free result set
        $result->free();
    } else {
        // Handle query error if needed
        echo "Error: " . $mysqli->error;
    }
    // Return the array of user objects
    return $users;
}
function getSingleUser($userId) {
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // SQL query to fetch user details including address details from user and useradressdetails tables
    $sql = "SELECT `user`.`_id`, `user`.`firstName`, `user`.`lastName`, `user`.`gmail`, `user`.`password`, 
                   `user`.`isAdmin`, `user`.`status`, `user`.`createdAt`, `user`.`profilePicture`,
                   `useradressdetails`.`country`, `useradressdetails`.`city`, `useradressdetails`.`area`
            FROM `user`
            LEFT JOIN `useradressdetails` ON `useradressdetails`.`userId` = `user`.`_id`
            WHERE `user`.`_id` = ?";
    
    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind the parameter (userId) to the prepared statement
        $stmt->bind_param("i", $userId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if a user was found
        if ($result->num_rows > 0) {
            // Fetch user data
            $userData = $result->fetch_assoc();

            // Close the statement
            $stmt->close();

            // Create and return User object
            return new User(
                $userData['_id'],
                $userData['firstName'],
                $userData['lastName'],
                $userData['gmail'],
                $userData['profilePicture'],
                new UserDetails($userData['country'], $userData['city'], $userData['area']),
                null
            );
            }
        else {
            // No user found with the given userId
            $stmt->close();
            return null; // Or handle the error as per your application logic
        }
    } else {
        // Statement preparation failed
        echo "Error preparing statement: " . $mysqli->error;
        return null; // Or handle the error as per your application logic
    }
}
function blockUser($userId, $operate) {
    // Get the database connection
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();

    // Prepare the SQL statement with placeholders
    $sql = "UPDATE `user` SET status = ? WHERE _id = ?";
    
    // Initialize a prepared statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind the parameters to the statement
        $stmt->bind_param("ii", $operate, $userId);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Successfully updated the user status
            return true;
        } else {
            // Handle execution error
            echo "Error updating user status: " . $stmt->error;
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // Handle preparation error
        echo "Error preparing statement: " . $mysqli->error;
    }
}

/**filtering */
function searchUserByEmail($email) { 
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection();
    $sql = "SELECT `_id`, `firstName`, `lastName`, `gmail`, `password`, `isAdmin`, `status`, `createdAt`, `profilePicture` FROM `user` 
    WHERE isAdmin = 0 AND gmail = '$email'";

    // Initialize an empty array to store users
    $users = [];

    // Execute the SQL query
    $result = $mysqli->query($sql);

    // Check if the query was successful
    if ($result) {
        // Fetch associative array of users
        while ($row = $result->fetch_assoc()) {
            // Create user objects and add them to the $users array
            $users[] = new User(
                $row['_id'],
                $row['firstName'],
                $row['lastName'],
                $row['gmail'],
                $row['profilePicture'],
                null,
                null
            );
        }

        // Free result set
        $result->free();
    } else {
        // Handle query error if needed
        echo "Error: " . $mysqli->error;
    }
    // Return the array of user objects
    return $users;
}