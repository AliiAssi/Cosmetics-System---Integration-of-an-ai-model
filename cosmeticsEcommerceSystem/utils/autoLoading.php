<?php 
spl_autoload_register(function ($class_name) {
    // Convert class name to file path
    $file_path = '../../Model/' . $class_name . '.php';
    $file_path_from_admin = '../../../Model/' . $class_name . '.php';
    $file_path_from_router = '../Model/'. $class_name . '.php';

    // Check if the file exists
    if (file_exists($file_path)) {
        // Load the class file
        require_once $file_path;
    }
    if (file_exists($file_path_from_admin)) {
        // Load the class file
        require_once $file_path_from_admin;
    }
    if (file_exists($file_path_from_router)) {
        // Load the class file
        require_once $file_path_from_router ;
    }
});

