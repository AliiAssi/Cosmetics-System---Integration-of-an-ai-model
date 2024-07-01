<?php
include '../../utils/autoLoading.php';
include '../../utils/ConnectionToDb.php';
include '../../Controller/CartController.php';
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
    <title>Beautiflie Products Menu</title>
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
    <link rel="stylesheet" href="../css/checkout.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<!-- product section start -->
<?php include '../components/header.php';?>
<?php
if(!isset($_SESSION['user'])){
    header("Location:./login.php");
}
$cartItems = getUserCart($_SESSION['user']->_id);
if(empty($cartItems)){
    header("Location:./cart.php");
}
?>
<?php
if(!isset($_SESSION['user']->userDetails->country)){
?>
<script>
    window.location.href = "profile.php?must=1";
</script>
<?php
}
?>

<body style="background-color: #fef4ec;">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 mt-1">
                <div class="card">
                    <div class="card-body">
                    <?php if(isset($_SESSION['error'])):?>
                        <div class="alert alert-danger text-center"><?=$_SESSION['error'];?> *_*</div>
                    <?php 
                    unset($_SESSION['error']);
                    endif;
                    ?>
                        <ol class="activity-checkout mb-0 px-4 mt-3">
                            <li class="checkout-item">
                                <div class="avatar checkout-icon p-1">
                                    <div class="avatar-title rounded-circle" style="background-color: #6f4e37;">
                                        <i class="bx bxs-receipt text-white font-size-20"></i>
                                    </div>
                                </div>
                                <div class="feed-item-list">
                                    <div>
                                        <h5 class="font-size-16 mb-1">address Info</h5>
                                        <div class="mb-3">
                                            <form>
                                                <span>
                                                    <b id="userAddress"> <?=$_SESSION['user']->userDetails->country?>/
                                                        <?=$_SESSION['user']->userDetails->city?>/
                                                        <?=$_SESSION['user']->userDetails->area?></b>
                                                </span>
                                                <br>
                                                <br>
                                                <a class="btn1" style="color: #fef4ec;" onclick="changeAddress(
                                                    '<?=$_SESSION['user']->userDetails->country;?>',
                                                    '<?=$_SESSION['user']->userDetails->city;?>',
                                                    '<?=$_SESSION['user']->userDetails->area;?>'
                                                    )">change address</a>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="checkout-item">
                                <div class="avatar checkout-icon p-1">
                                    <div class="avatar-title rounded-circle " style="background-color: #6f4e37;">
                                        <i class="bx bxs-wallet-alt text-white font-size-20"></i>
                                    </div>
                                </div>
                                <div class="feed-item-list">
                                    <div>
                                        <h5 class="font-size-16 mb-1">Payment Info</h5>
                                        <p class="text-muted text-truncate mb-4"></p>
                                    </div>
                                    <div>
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <form>
                                                    <div data-bs-toggle="collapse">
                                                        <label class="card-radio-label">
                                                            <input type="radio" onclick="credit()" name="pay-method"
                                                                id="pay-methodoption1" class="card-radio-input">
                                                            <span class="card-radio py-3 text-center text-truncate">
                                                                <i class="bx bx-credit-card d-block h2 mb-3"></i>
                                                                Credit / Debit Card
                                                            </span>
                                                        </label>
                                                    </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <div>
                                                    <label class="card-radio-label">
                                                        <input type="radio" onclick="paypal()" name="pay-method"
                                                            id="pay-methodoption2" class="card-radio-input">
                                                        <span class="card-radio py-3 text-center text-truncate">
                                                            <i class="bx bxl-paypal d-block h2 mb-3"></i>
                                                            Paypal
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <div>
                                                    <label class="card-radio-label">
                                                        <input type="radio" onclick="delivery()" name="pay-method"
                                                            id="pay-methodoption3" class="card-radio-input" checked="">
                                                        <span class="card-radio py-3 text-center text-truncate">
                                                            <i class="bx bx-money d-block h2 mb-3"></i>
                                                            <span>Cash on Delivery</span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </from>
                                </div>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="row my-4">
                    <div class="col">
                        <a href="./cart.php" class="btn btn-link text-muted">
                            <i class="mdi mdi-arrow-left me-1"></i>back </a>
                    </div>
                    <div class="col">
                        <div class="text-end mt-2 mt-sm-0">
                            <a href="../../routes/orderRoute.php?add=1" class="btn proceed-btn">
                                <i class="mdi mdi-cart-outline me-1"></i> Proceed
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 mt-1">
                <div class="card checkout-order-summary">
                    <div class="card-body">
                        <div class="p-3 bg-light mb-3">
                            <h5 class="font-size-16 mb-0">Order Summary <span class="float-end ms-2">   </span></h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-centered mb-0 table-nowrap">
                                <thead>
                                    <tr>
                                        <th class="border-top-0" style="width: 110px;" scope="col">Product</th>
                                        <th class="border-top-0" scope="col">Product Desc</th>
                                        <th class="border-top-0" scope="col">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totale = 0;
                                    foreach ($cartItems as $cartItem):
                                        $product = $cartItem->product;
                                        $totale += number_format($product->getPrice() - ($product->getPrice() * $product->getDiscount() / 100), 2) * $cartItem->qty;
                                    ?>
                                    <tr>
                                        <th scope="row"><img src="../uploaded_img/<?=$product->getPicture()?>" alt="<?=$product->getPicture()?>"
                                                title="product-img" class="avatar-lg rounded"></th>
                                        <td>
                                            <h5 class="font-size-16 text-truncate"><span href="#"
                                                    class="text-dark"><?=$product->getName()?></span></h5>
                                            <p class="text-muted mb-0 mt-1">$<?=number_format($product->getPrice() - ($product->getPrice() * $product->getDiscount() / 100), 2)?> x <?=$cartItem->qty;?></p>
                                        </td>
                                        <td>$<?=number_format($product->getPrice() - ($product->getPrice() * $product->getDiscount() / 100), 2) * $cartItem->qty;?></td>
                                    </tr>
                                    <?php endforeach;?>
                                    <td colspan="2">
                                        <h5 class="font-size-14 m-0">Estimated Tax :</h5>
                                    </td>
                                    <td>
                                        $ 0.0
                                    </td>
                                    </tr>
                                    <tr class="bg-light">
                                        <td colspan="2">
                                            <h5 class="font-size-14 m-0">Total:</h5>
                                        </td>
                                        <td>
                                            $<?=$totale;?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../components/footer.php';?>
</body>

</html>
<script src="../js/checkout.js"></script>