<?php
include '../../utils/autoLoading.php';
include '../../utils/ConnectionToDb.php';
include '../../Controller/OrderController.php';
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
    <link rel="stylesheet" href="../css/history.css">
    <link rel="stylesheet" href="../css/trackingOrder.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<!-- product section start -->
<?php include '../components/header.php';?>
<?php 
$orders = [] ;
if(isset($_GET['all'])){
    $orders  = getOrders($_SESSION['user']->_id);
}
else if (isset($_GET['track'])){
    $order = getOrder($_SESSION['user']->_id,$_GET['track']);
    if($order == null){
        $orders = [];
    }else{
        $orders[] = $order;
    }
}
else{
    $lastOrder = getLastOrder($_SESSION['user']->_id);
    $orders [] = $lastOrder;
    if($lastOrder == null){
        $orders = [];
    }
}
$seeAll = countUserOrders($_SESSION['user']->_id);
?>

<body style="background-color: #fef4ec;">
    <div class="container">

        <?php if(count($orders) > 1): ?>
        <!--
        <div class="row" id="filtering">
            <div class="col">
                <label for="sorting-by" class="custom-label">Sorting By</label>
                <select name="sort" id="sorting-by" class="custom-select">
                    <option value="price">Price</option>
                    <option value="placedOn">Placed On</option>
                </select>
            </div>
            <div class="col">
                <label for="sorting-by" class="custom-label">filtered by</label>
                <select name="filter" id="" class="custom-select">
                    <option value="pending">pending</option>
                    <option value="completed">completed</option>
                </select>
            </div>
        </div>
        -->
        <div class="alert mt-5"></div>
        <?php endif; ?>
        <?php if(empty($orders)): ?>
        <script>
            location.href = 'products.php?message=1';
        </script>
        <?php else : ?>
        <?php if(count($orders) == 1):
              $order = $orders[0];
        ?>
        <div class="container">
            <div class="card">
                <div class="title">last order</div>
                <div class="info">
                    <div class="row">
                        <div class="col-7">
                            <span id="heading">Date</span><br>
                            <span id="details"><?=$order->createdAt?></span>
                        </div>
                        <div class="col-5 pull-right">
                            <span id="heading">target Adress</span><br>
                            <span id="details"><?=$order->address?></span>
                        </div>
                    </div>
                </div>
                <div class="pricing">
                    <?php foreach($order->orderItems as $orderItem):?>
                    <!-- products -->
                    <div class="row">
                        <div class="col-9">
                            <span id="name"><?=$orderItem->product->getName()?></span>
                        </div>
                        <div class="col-3">
                            <span id="price"><?=$orderItem->productPrice?> * <?=$orderItem->qty?></span>
                        </div>
                    </div>
                    <!--end row-->
                    <?php endforeach; ?>

                </div>
                <div class="total">
                    <div class="row">
                        <div class="col-9">TOTAL</div>
                        <div class="col-3"><big>$<?=$order->totale?></big></div>
                    </div>
                </div>
                <div class="tracking">
                    <div class="title">Tracking Order</div>
                </div>

                <?php
                $status = $order->status;
                $placedOn = $order->createdAt;
                if($status === "completed"){
                    $now = new DateTime();
                    $date =  new DateTime($placedOn);
                    $interval = $date->diff($now);
                }
                if($status == "placed"){
                ?>
                <div class="progress-track">
                    <ul id="progressbar">
                        <li class="step0 active " id="step1">Ordered</li>
                        <li class="step0  text-center" id="step2">pending</li>
                        <li class="step0   text-right" id="step3">On the way</li>
                        <li class="step0  text-right" id="step4">completed</li>
                    </ul>
                </div>
                <?php } ?>
                <?php
            if($status == "accepted"){
            ?>
                <div class="progress-track">
                    <ul id="progressbar">
                        <li class="step0 active " id="step1">Ordered</li>
                        <li class="step0  active text-center" id="step2">pending</li>
                        <li class="step0   text-right" id="step3">On the way</li>
                        <li class="step0  text-right" id="step4">completed</li>
                    </ul>
                </div>
                <?php } ?>
                <?php
            if($status == "completed" && !isset($interval) || (isset($interval) && $interval->days < 1)){
            ?>
                <div class="progress-track">
                    <ul id="progressbar">
                        <li class="step0 active " id="step1">Ordered</li>
                        <li class="step0  active text-center" id="step2">pending</li>
                        <li class="step0 active  text-right" id="step3">On the way</li>
                        <li class="step0  text-right" id="step4">completed</li>
                    </ul>
                </div>
                <?php } ?>
                <?php

            if($status === "completed" && isset($interval) && $interval->days >= 1){
            ?>
                <div class="progress-track">
                    <ul id="progressbar">
                        <li class="step0 active " id="step1">Ordered</li>
                        <li class="step0  active text-center" id="step2">pending</li>
                        <li class="step0 active  text-right" id="step3">On the way</li>
                        <li class="step0 active  text-right" id="step4">completed</li>
                    </ul>
                </div>
                <?php } ?>
                
            </div>
        </div>
        <?php if(count($orders) > 1 || $seeAll > 1): ?>
        <div class="mr-5 container d-flex justify-content-center mb-2">
            <a class="btn btn-primary btn-lg" href="history.php?all=1"
                style="background-color: #ff69b4; border-color: #ff69b4;">See all orders</a>
        </div>
        <?php endif; ?>
        <?php else : ?>
        <table class="table table-striped custom-table" id="table">
            <tr>
                <th>ID</th>
                <th>few details</th>
                <th>placed on</th>
                <th>Totale</th>
                <th>Action</th>
                <th>track</th>
            </tr>
            <?php foreach($orders as $order): ?>
            <tr id="tr<?=$order->_id?>">
                <td><?=$order->_id?></td>
                <td>
                    <details>
                        <summary>Items</summary>
                        <?php foreach($order->orderItems as $item): ?>
                        <datagrid><?=$item->product->getName()?></datagrid> <br>
                        <?php endforeach; ?>
                    </details>
                </td>
                <td><?=$order->createdAt;?></td>
                <td><?=$order->totale?>$</td>
                <td>
                    <?php if ($order->status == 'accepted' || $order->status == 'completed' ) : ?>
                    <a class="link " onclick="cannot()">
                        <span class="icon">🚫</span> Cancel
                    </a>
                    <?php else : ?>
                    <a class="link" onclick="cancel('<?=$order->_id;?>')">
                        <span class="icon">🗑️</span> Cancel
                    </a>
                    <?php endif; ?>
                </td>
                <td>
                    <a class="link" href="history.php?track=<?=$order->_id?>">
                        <span class="icon">📍</span> Details
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <!-- Add more rows for additional products as needed -->
        </table>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
<?php include '../components/footer.php';?>
</body>

</html>
<script src="../js/history.js"></script>

<?php if(isset($_SESSION['message'])): ?>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    Toastify({
        text: "ORDER CREATED SUCCSEFULLY ^_^",
        duration: 3500,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "center", // `left`, `center` or `right`
        style: {
            background: "linear-gradient(to right, #00b09b, #96c93d)",
        }
    }).showToast();
</script>
<?php unset($_SESSION['message']); endif; ?>