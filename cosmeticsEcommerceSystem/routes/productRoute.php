<?php
include '../utils/autoLoading.php';
include '../utils/ConnectionToDb.php';
include '../Controller/ProductController.php';
session_start();
function favoriteRedirect(){
    if(isset($_GET['redirect'])){
        $_SESSION['redirected'] = 1;
        header('Location:../View/pages/products.php?fav=1');
        exit();
    }
}
favoriteRedirect();

/**
 * part 2
*/

if(isset($_GET['deleteId'])){
    $id =   $_GET['deleteId'];
    echo deleteProduct($id);
}

if(isset($_POST['edit'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['desc'];
    $discount = $_POST['discount'];
    $categoryId = $_POST['categoryId'];
    $qty = $_POST['qty'];
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

        // Move uploaded file to the specified directory
        if(move_uploaded_file($fileTmpName, $uploadPath)) {
            // Call the editProduct function to update product details
            $editResult = editProduct($id, $name, $desc, $price, $discount, $qty, $categoryId, $uniqueFileName);

            // Display result or handle further logic based on $editResult
            echo $editResult;
            $_SESSION['message'] = $editResult;
        } else {
            $_SESSION['message']  = "Failed to upload image.";
        }
    } else {
        // Handle cases where there's an upload error
        // Call the editProduct function to update product details
        $editResult = editProduct($id, $name, $desc, $price, $discount, $qty, $categoryId, null);

        // Display result or handle further logic based on $editResult
        echo $editResult;
        $_SESSION['message'] = $editResult;
    }
    header('Location:../View/pages/manipulateProduct.php?id='.$id);
}

if (isset($_POST['insert'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['desc'];
    $discount = $_POST['discount'];
    $categoryId = $_POST['categoryId'];
    $qty = $_POST['qty'];
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

        // Move uploaded file to the specified directory
        if(move_uploaded_file($fileTmpName, $uploadPath)) {
            // Call the editProduct function to update product details
            $editResult = insertProduct($name, $desc, $price, $discount, $qty, $categoryId, $uniqueFileName);

            // Display result or handle further logic based on $editResult
            echo $editResult;
            $_SESSION['message'] = $editResult;
        } else {
            $_SESSION['message']  = "Failed to upload image.";
        }
    } else {
        $editResult = insertProduct($name, $desc, $price, $discount, $qty, $categoryId, null);
        $_SESSION['message'] = $editResult;
    }
    header('Location:../View/pages/products.php');
}
