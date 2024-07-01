<?php
include '../utils/ConnectionToDb.php';
include '../utils/autoLoading.php';
include '../Controller/BannerController.php';
session_start();
// handling delete banner 
if(isset($_GET['id'])){
    $id = $_GET['id'];

    $count = countBanners();
    if($count == 1){
        echo 'cannot';
        exit();
    }
    deleteBanner($id);
    exit();
}
//handling updatBanner function
if(isset($_POST['edit'])){
    $id = $_POST['id'];        

    if(isset($_FILES['image'])){
        $file = $_FILES['image'];
        // File properties
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name']; // Temporary file path
        $uniqueFileName = uniqid('image_') . '_' . $fileName;
        
        // Destination folder
        $uploadDirectory = '../View/images/'; // Change this to your desired folder
        
        // Move the uploaded file to the destination folder
        if(move_uploaded_file($fileTmpName, $uploadDirectory . $uniqueFileName)){
            // File moved successfully, update the banner image in the database
            $res = updateBannerImage($id, $uniqueFileName);
            if($res){
                // Set session variable for successful update
                $_SESSION['edit'] = true;
            } else {
                // Set session variable for database update failure
                $_SESSION['edit'] = false;
            }
        } else {
            // Set session variable for file move failure
            $_SESSION['edit'] = true;
        }
    }
    // Update banner title regardless of whether image is uploaded or not
    updateBannerTitle($id, $_POST['title']);
    header("Location:../View/pages/banner.php");
}

//handling insert function
if(isset($_POST['insert'])){
    $title  = $_POST['title'];
    if(isset($_FILES['image'])){
        $file = $_FILES['image'];
        // File properties
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name']; // Temporary file path
        $uniqueFileName = uniqid('image_') . '_' . $fileName;
        
        // Destination folder
        $uploadDirectory = '../View/images/'; // Change this to your desired folder
        
        // Move the uploaded file to the destination folder
        if(move_uploaded_file($fileTmpName, $uploadDirectory . $uniqueFileName)){
            // File moved successfully, update the banner image in the database
            $res = insertBanner($title, $uniqueFileName);
            if($res){
                // Set session variable for successful update
                $_SESSION['insert'] = true;
            } else {
                // Set session variable for database update failure
                $_SESSION['insert'] = false;
            }
        } else {
            // Set session variable for file move failure
            $_SESSION['insert'] = false;
        }
    }
    // Update banner title regardless of whether image is uploaded or not
    header("Location:../View/pages/banner.php");
}

