<?php
include '../../utils/autoLoading.php';
include '../../utils/ConnectionToDb.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beautiflie Reviews</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <!-- Custom Styles -->
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <!-- Responsive Styles -->
    <link rel="stylesheet" href="../css/responsive.css">
    <!-- Custom Scrollbar CSS -->
    <link rel="stylesheet" href="../css/jquery.mCustomScrollbar.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Open+Sans:400,700&display=swap&subset=latin-ext" rel="stylesheet">
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <!-- Gijgo Datepicker CSS -->
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <!-- Contact Form CSS -->
    <link rel="stylesheet" href="../css/contact.css">
    <!-- Custom Review Card Styles -->
    <style>
        .review-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .review-card .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .review-card .review-content {
            margin-top: 10px;
        }

        .review-card .user-name {
            font-weight: bold;
        }

        .review-card .review-text {
            color: #666;
        }
    </style>
</head>

<body style="background-color: #fef4ec;">

    <?php include '../components/header.php'; ?>
    <?php
    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        $_SESSION['toasty'] = 1;
        header("LOCATION:login.php");
    }

    // Include necessary controllers
    include '../../Controller/FeedBackController.php';
    include '../../Controller/UserController.php';

    // Fetch all feedbacks
    $feedbacks = getAllFeedbacks(); 
    ?>

    <section class="ftco-section">
        <div class="container">
            <h2 class="text-center mb-4">User Reviews</h2>

            <div class="row">
                <?php foreach ($feedbacks as $feedback) : ?>
                    <?php $user = getSingleUser($feedback->user_id); ?>
                    <div class="col-md-4">
                        <div class="review-card">
                            <div class="user-info">
                                <img src="../uploaded_img/<?= $user->profilePicture; ?>" alt="User Avatar" class="user-avatar">
                                <div>
                                <p class="font-weight-bold mb-0"><?= $user->firstName . ' ' . $user->lastName; ?></p>
                                <p class="mb-0">HOW MUCH :      <?= $feedback->howMuch; ?>%</p>
                                <p class="mb-0">acceptance :    <?php if($feedback->homeDisplayed == 1) echo $feedback->acceptance; else echo 100 - $feedback->acceptance; ?>%</p>
                                </div>
                            </div>
                            <p class="review-text">feedback :   <?= htmlspecialchars($feedback->content); ?></p>
                            <div>
                                <a href="../../routes/feedBackRoute.php?feedId=<?=$feedback->_id;?>&t=0" class="btn btn-danger">delete</a>
                                <?php if($feedback->homeDisplayed === 0): ?>
                                <a href="../../routes/feedBackRoute.php?feedId=<?=$feedback->_id;?>&t=1" class="btn btn-success">display</a>
                                <?php  endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Add more reviews in similar structure if needed -->

        </div>
    </section>

    <?php include '../components/footer.php'; ?>

</body>

</html>
