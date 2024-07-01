<?php
session_start();
include '../Controller/AuthController.php';
function processSignUpRequest() {
    if(isset($_POST['auth']) && $_POST['auth'] === "signup") {
        // Sanitize and validate each parameter
        $sanitizedParams = [];
        $validations = [
            'firstName' => '/^[a-zA-Z]{1,20}$/',
            'lastName' => '/^[a-zA-Z]{1,20}$/',
            'email' => '/^\S+@\S+\.\S+$/'
        ];
        // validate
        foreach ($_POST as $key => $value) { // post = auth , firstName , lastNAme, email m, password
            if($key === 'auth') {
                continue;
            }
            // Sanitize input
            $sanitizedValue = htmlspecialchars(trim($value));
            
            // Validate input
            if (isset($validations[$key]) && !preg_match($validations[$key], $sanitizedValue)) {
                $_SESSION['message'] = 'Invalid ' . ucfirst($key) . ' format';
                header('location:../View/pages/signup.php');
            }
        
            // Store sanitized parameters
            $sanitizedParams[$key] = $sanitizedValue;
        }
        // insert
        if(insertUser($sanitizedParams['firstName'] , $sanitizedParams['lastName'] , $sanitizedParams['email'] , $_POST['password'] )){
            $_SESSION['message'] = 'inserted successfully';
            header('Location:../View/pages/login.php');
        }else{
            $_SESSION['message'] = 'inserted was broken maybe you should just log in ';
            header('Location:../View/pages/signup.php');
        }
    }
}

function processLoginInRequest() {
    
    if(isset($_POST['auth']) && $_POST['auth'] === "login") {
        // Check if email and password are provided
        if(isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Email validation
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['message'] = 'Invalid email format provided';
                header('Location: ../View/pages/login.php');
                exit();
            }

            // Password validation
            if(strlen($password) < 5) {
                $_SESSION['message'] = 'Password must be at least 5 characters long';
                header('Location: ../View/pages/login.php');
                exit();
            }

            // Call loginUser function to attempt login
            $user = loginUser($email, $password); //blocked
            $ok = true;
            if($user == "blocked"){
                $ok = !$ok;
            }            
            if($user && $ok){
                // Login successful
                // Store user information in session
                $_SESSION['user'] = $user;
                echo $_SESSION['user']->isAdmin;
                // Redirect to the user dashboard or any other page
                header('Location: ../View/pages/home.php');
                exit();
            } else {
                // Invalid credentials
                $_SESSION['message'] = 'Invalid credentials ';
                if(!$ok){
                    $_SESSION['message'] = "user ".$user;
                }
                header('Location: ../View/pages/login.php');
                exit();
            }
        } else {
            // Missing email or password
            $_SESSION['message'] = 'Missing email or password';
            header('Location: ../View/pages/login.php');
            exit();
        }
    }
}
function processLogout() {
    // Check if user is logged in and logout parameter is set in the URL
    if (isset($_SESSION['user']) && isset($_GET['logout'])) {
        // Unset all session variables
        session_unset();
        // Destroy the session
        session_destroy();
        // Redirect the user to the login page or any other desired page
        header("Location:../View/pages/login.php");
        exit; // Make sure to exit after redirection
    }
}

processLogout();

// Call the function to process the login request
processLoginInRequest();

// Call the function to process the signup request
processSignUpRequest();
