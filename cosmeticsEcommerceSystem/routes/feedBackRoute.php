<?php
function createFeedback() {
    include '../utils/ConnectionToDb.php';
    include '../Controller/FeedBackController.php';
    include '../utils/autoLoading.php';
    session_start();

    if(isset($_POST['createFeedBack']) && isset($_POST['howMuch']) && isset($_POST['message'])) {
        // Validate and process the feedback creation
        $howMuch = $_POST['howMuch'];
        $feedback = $_POST['message'];

        // Validate input parameters
        if (!is_numeric($howMuch) || $howMuch < 1 || $howMuch > 100) {
            $_SESSION['message'] = 'error in the format of feedback range';
            header("LOCATION:../View/pages/contact.php");
        }

        if (empty($feedback)) {
            $_SESSION['message'] .= 'empty feedback lol !';
            header("LOCATION:../View/pages/contact.php");
        }

        if(!insertFeedbackIntoDatabase($feedback, $howMuch, $_SESSION['user']->_id)){
            $_SESSION['message'] = 'error in the insertion !';
            header("LOCATION:../View/pages/contact.php");
        }
        $_SESSION['message'] = 'feedBack has inserted successfully !';
        header("LOCATION:../View/pages/contact.php");
        
    }
    else{
        // can we show the body of the request 
        $_SESSION['message'] = 'error in the request  !';
        header("LOCATION:../View/pages/contact.php");
    }
}

createFeedback();

/**
 * part 2
*/

if(isset($_GET['feedId'])){
    $feedId = $_GET['feedId'];
    $res = false ;
    if ($_GET['t'] == 0)    $res = deleteReview($feedId);
    if ($_GET['t'] == 1)    $res = display($feedId);
    $_SESSION['message'] = "Feed processed successfully";
    if (!$res)  $_SESSION['message'] = "Feed error";
    header("LOCATION:../View/pages/reviews.php");

}
