<?php
include '../../utils/autoLoading.php';
include '../../utils/ConnectionToDb.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>Beautiflie Notifications</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="../css/responsive.css">
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="../css/jquery.mCustomScrollbar.min.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Open+Sans:400,700&display=swap&subset=latin-ext"
        rel="stylesheet">
    <!-- owl stylesheets -->
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css"
        media="screen">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/contact.css">
</head>

<body style="background-color: #fef4ec;">
    <?php include '../components/header.php';?>
    <?php 
    if(!isset($_SESSION['user'])){
        $_SESSION['toasty'] = 1;
        header("LOCATION:login.php");
    }
    include '../../Controller/NotificationController.php';
    include '../../Controller/ProductController.php';
    $notifications = getUserNotifications($_SESSION['user']->_id);
    $orderNotifcations = getUserOrdersNotifications($_SESSION['user']->_id);
    if(isset($_GET['seen'])){
        markAsSeen($_SESSION['user']->_id);
    }
    ?>

    <section class="ftco-section">
        <div class="container">
            <h2 class="text-center mb-4">User Notifications</h2>
            <?php if(count($notifications) < 1 && count($orderNotifcations) < 1): ?>
                <div class="alert" class="mt-2 mb-2"></div>
                <div class="alert" class="mt-2 mb-2"></div>
                <div class="alert alert-info mt-2 mb-2">
                    <span>no notifications yet</span>
                </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-4">
                        <a href="notification.php?seen=1" class="btn btn-primary">mark as seen notifications</a>
                    </div>
                </div>
                <div class="row">
                <?php foreach($notifications as $notification): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="media">
                                <img src="../images/img-1.png" alt="Product Image"
                                    class="mr-3 img-thumbnail" style="width: 100px; height: 100px;">
                                <div class="media-body">
                                    <h5 class="mt-0"><?= $notification->product->getName(); ?></h5>
                                    <p><?= $notification->content; ?></p>
                                    <small class="text-muted">Received on: <?= $notification->createdAt; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php foreach($orderNotifcations as $order_notification):
                $ordersId [] = $order_notification['orderId'];
                ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="mt-0"></h5>
                                    <p><?= $order_notification['content']; ?></p>
                                    <small class="text-muted">Received on: <?= $order_notification['createdAt']; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php include '../components/footer.php';?>
</body>
</html>
