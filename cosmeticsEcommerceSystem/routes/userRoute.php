<?php
include '../utils/autoLoading.php';



function deleteProfilePicture(){
    if(isset($_GET['image']) && $_GET['image'] == "delete"){
        session_start();
        include '../utils/ConnectionToDb.php';
        include '../Controller/UserController.php';

        if(!deleteUserProfilePicture($_SESSION['user']->_id)){
            $_SESSION['d-pp'] = 'cannot delete default profile picture';
        }
        $_SESSION['d-pp'] = 'deleted successfully';
        $user = new User($_SESSION['user']->_id, $_SESSION['user']->firstName, $_SESSION['user']->lastName, $_SESSION['user']->email,'default.jpg', $_SESSION['user']->userDetails,$_SESSION['user']->isAdmin);
        unset($_SESSION['user']);
        $_SESSION['user'] = $user;
        header("Location:../View/pages/profile.php");
    }
}

function handlingChangePasswordRequest() {
    
    if (isset($_POST['changePassword'])) { // Check if 'changePassword' is set
        session_start();
        include '../utils/ConnectionToDb.php';
        include '../Controller/UserController.php';

        if (!isset($_POST['old']) || $_POST['old'] == "") {
            $_SESSION['message'] = "Please enter old password";
            header("Location:../View/pages/profile.php");
            exit(); // Exit after redirect
        }
        if (!isset($_POST['new']) || $_POST['new'] == "") {
            $_SESSION['message'] = "Please enter new password";
            header("Location:../View/pages/profile.php");
            exit(); // Exit after redirect
        }
        echo $_SESSION['user']->_id;
        if(!changeUserPassword($_POST['old'] , $_POST['new'], $_SESSION['user']->_id)) {
            $_SESSION['message'] = "Please enter the right old password";
            header("Location:../View/pages/profile.php");
            exit(); // Exit after redirect
        }
        $_SESSION['message'] = "Change Password successfully";
        header("Location:../View/pages/profile.php");
        exit(); // Exit after redirect
    }
}

function handlingUserUpdateItsProfilePicture(){
    
    if(isset($_POST['image-updated'])){
        session_start();
        include '../utils/ConnectionToDb.php';
        include '../Controller/UserController.php';
        if(!isset($_FILES['image'])){
            $_SESSION['pp-err'] = 'image not found';
            header('Location:../View/pages/profile.php');
            exit();
        }
        $file = $_FILES['image'];
        // File properties
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileError = $file['error'];
        // Check if there is no error
        if($fileError === 0) {
        // Specify the directory where you want to store the image
        $uploadDir = '../View/uploaded_img/';
        // Create the directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Generate a unique name for the image to prevent overwriting
        $uniqueFileName = uniqid('image_') . '_' . $fileName;
        $uploadPath = $uploadDir . $uniqueFileName;

        // Move the uploaded file to the specified directory
        if(move_uploaded_file($fileTmpName, $uploadPath)) {
            $user = new User($_SESSION['user']->_id, $_SESSION['user']->firstName, $_SESSION['user']->lastName, $_SESSION['user']->email,$uniqueFileName, $_SESSION['user']->userDetails , $_SESSION['user']->isAdmin);
            unset($_SESSION['user']);
            $_SESSION['user'] = $user;

            if(!updateUserProfilePicture($_SESSION['user']->_id , $uniqueFileName)){
                $_SESSION['pp-err'] = 'failed to update user profile picture';
                header('Location:../View/pages/profile.php');
                exit();
            }
            $_SESSION['pp'] = 'profile picture updated successfully'; 
            header('Location:../View/pages/profile.php');
            exit();

        } else {
            $_SESSION['pp'] = 'failed to upload this file';
            header('Location:../View/pages/profile.php');
            exit();
        }
    } else {
        echo "Error uploading file: " . $fileError;
    }  

    }
}

function updateUserProfile() {
    if(isset($_POST['updateUserProfile'])) {
        session_start();
        include '../utils/ConnectionToDb.php';
        include '../Controller/UserController.php';
        
        $requiredFields = ["firstName", "lastName", "email", "country", "city", "area"];
        
        // Check if all required fields are set in $_POST
        foreach($requiredFields as $field) {
            if(!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $_SESSION['update-message'] = $field.' is not set';
                header('Location:../View/pages/profile.php');
                exit(); // Terminate script after redirect
            }
        }
        
        // Call updateUserProfileDetails function from UserController to update user profile
        $error = updateUserProfileDetails(
            $_SESSION['user']->_id,
            $_POST['firstName'],
            $_POST['lastName'],
            $_POST['email'],
            $_POST['country'],
            $_POST['city'],
            $_POST['area'],
            $_POST['hidden']
        );
        
        if ($error !== true) {
            $_SESSION['update-message'] = 'An error occurred: ' . $error;
            header('Location:../View/pages/profile.php');
            exit(); // Terminate script after redirect
        } else {
            $_SESSION['update-message'] = 'User info changed successfully';
            header('Location:../View/pages/profile.php');
            exit(); // Terminate script after redirect
        }
    }
}

handlingChangePasswordRequest();
handlingUserUpdateItsProfilePicture();
deleteProfilePicture() ;
updateUserProfile();

/**route for changing the address of a user from checkout.php */
if (isset($_GET['country']) && isset($_GET['city']) && isset($_GET['area'])) {
    session_start(); // Start the session
    include '../utils/ConnectionToDb.php'; // Include the database connection file
    $mysqli = SingletonConnectionToDb::getInstance()->getConnection(); // Get the database connection instance
    $userId = $_SESSION['user']->_id; // Get the user ID from the session
    $sql = "UPDATE useradressdetails SET country=?, city=?, area=? WHERE userId=?";
    // Prepare the statement
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sssi", $_GET['country'], $_GET['city'], $_GET['area'], $userId);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Update successful
            $updatedUser = new User($_SESSION['user']->_id, $_SESSION['user']->firstName, $_SESSION['user']->lastName, $_SESSION['user']->email ,$_SESSION['user']->profilePicture, 
            new UserDetails($_GET['country'], $_GET['city'], $_GET['area'] ), $_SESSION['user']->isAdmin
            );
            unset($_SESSION['user']);
            $_SESSION['user'] = $updatedUser;
            echo "Address details updated successfully.";
        } else {
            // Update failed
            echo "Error updating address details: " . $mysqli->error;
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // Statement preparation failed
        echo "Error preparing statement: " . $mysqli->error;
    }
}


/** route for blocking unblocking a user */
if (isset($_GET['user_id']) && isset($_GET['operate'])){
    session_start();
    include '../utils/ConnectionToDb.php';
    include '../Controller/UserController.php';
    if (blockUser($_GET['user_id'], $_GET['operate'])){
        echo 'done';
    }
    echo 'undone';
}