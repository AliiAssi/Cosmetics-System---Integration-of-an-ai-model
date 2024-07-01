<?php
include '../components/cart/cartProductItem.php';
include '../../utils/autoLoading.php';
include '../../utils/ConnectionToDb.php';
include '../../Controller/CartController.php';
?>
<?php

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
    <title>Beautiflie MyCart</title>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<!-- product section start -->
<?php include '../components/header.php';?>
<?php
$items = getUserCart($_SESSION['user']->_id);
?>

<body style="background-color: #fef4ec;">
    <!--cart template --->
    <div class="mt-2" style="height:100px"></div>
    <div class="container mb-5">
        <div class="d-flex justify-content-center row">
            <div class="col-md-8">
                <div class="p-2">
                    <h4>Shopping Cart</h4>
                </div>
                <div id="here">
                </div>
                <?php if(empty($items)): ?>
                    <div class="alert alert-danger mt-5 text-center">
                        <strong>Alert!</strong>
                        <p>Dear valued customer, you cart is empty ^_^</p>
                    </div>
                <?php else: ?>
                    <?php
                    foreach ($items as $cartItem):
                        echo generateProductItemDiv($cartItem->product,$cartItem->qty);
                    endforeach;
                    ?>
                    <div class="d-flex flex-row align-items-center mt-3 p-2 bg-white rounded" id="checki"><button
                            class="btn btn-warning btn-block btn-lg ml-2 pay-button" type="button" onclick="toPage()">Proceed to Checkout</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="mt-1" style="height:100px"></div>
    <?php include '../components/footer.php';?>
</body>

</html>
<script src="../js/cart.js"></script>    