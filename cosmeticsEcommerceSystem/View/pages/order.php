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
    <title>Beautiflie Orders</title>
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
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Open+Sans:400,700&display=swap&subset=latin-ext" rel="stylesheet">
    <!-- owl stylesheets -->
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/contact.css">
    <link rel="stylesheet" href="../css/admin/orders.css">
    <style>
        .card {
            margin-bottom: 20px;
        }
        .card img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .card-body {
            text-align: center;
        }
        .order-items {
            text-align: left;
            margin-top: 10px;
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
            padding: 5px 0;
        }
        .item-name {
            font-weight: bold;
        }
        .item-qty {
            color: #555;
        }
        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
    <script>
        function statusChange(newStatus) {
            window.location.href = "./order.php?status=" + newStatus+"&c=1";
        }
    </script>
</head>
<?php 
if (!isset($_GET['status'])){
    header("Location:./order.php?status=placed&page=1");
}
include '../../Controller/OrderController.php';
$status = $_GET['status']; // This can be dynamic based on your application
$maxOrdersToFetch = 3; // Number of orders per page
$totalOrders = countOrders($status);
$totalPages = ceil($totalOrders / $maxOrdersToFetch);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Fetch orders for the current page
$orders = getOrdersByStatus($status, $maxOrdersToFetch, $currentPage);
?>
<body style="background-color: #fef4ec;">
    <?php include '../components/header.php';?>
    <?php 
    if(!isset($_SESSION['user'])){
        $_SESSION['toasty'] = 1;
        header("LOCATION:login.php");
    }
    ?>
    <section class="ftco-section">
        <section class="container">
            <select name="" id="status" onchange="statusChange(this.value)">
                <option value="placed" <?php if($_GET['status'] === "placed" ) echo "selected"  ?> >placed</option>
                <option value="accepted"<?php if($_GET['status'] === "accepted" ) echo "selected"  ?>>pending</option>
                <option value="completed" <?php if($_GET['status'] === "completed" ) echo "selected"  ?>>completed</option>
            </select>
        </section>
        <div class="container">
            <h2 class="text-center mb-4">Order Details</h2>
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert alert-danger"><?=$_SESSION['message'];?></div>
            <?php unset($_SESSION['message']); endif; ?>
            <div class="row">
                <!-- Example card for an order -->
                <?php foreach($orders as $order): ?>
                    <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <img src="../uploaded_img/default.jpg" alt="Profile Picture" class="card-img-top">
                            <h5 class="card-title"><?=$order->user->firstName?> <?=$order->user->lastName?></h5>
                            <p class="card-text"><?=$order->user->email;?></p>
                            <p class="card-text" style="color:#007bff"><?=$order->address?></p>
                            <p class="card-text">Order Number: <?=$order->_id;?></p>
                            <p class="card-text">Order Date: <?=$order->createdAt;?></p>
                            <div class="order-items">
                                <?php
                                $orderItems = $order->orderItems;
                                foreach ($orderItems as $orderItem): 
                                ?>
                                <div class="order-item">
                                    <span class="item-name"><?=$orderItem->product->getName();?></span>
                                    <span class="item-qty">Qty: <?=$orderItem->qty?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <p class="card-text">Total: $<b style="color:tomato"><?=$order->totale?></b></p>
                            <p class="card-text">Status: <?=$order->status;?></p>
                            <?php
                            if ($_GET['status'] == "placed"):
                            ?>
                                <a href="../../routes/orderRoute.php?orderId=<?=$order->_id?>&s=0" class="btn btn-danger btn-sm">Reject</a>
                                <a href="../../routes/orderRoute.php?orderId=<?=$order->_id?>&s=1" class="btn btn-success btn-sm">Accept</a>
                            <?php elseif($_GET['status'] == "accepted") :?>
                                <a href="../../routes/orderRoute.php?orderId=<?=$order->_id?>&s=2" class="btn btn-success btn-sm">On Way</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
            <!-- Pagination controls -->
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?status=<?php echo $status; ?>&page=<?php echo $currentPage - 1; ?>">&laquo;</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?status=<?php echo $status; ?>&page=<?php echo $i; ?>" class="<?php if ($i == $currentPage) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?status=<?php echo $status; ?>&page=<?php echo $currentPage + 1; ?>">&raquo;</a>
                <?php endif; ?>
            </div>

        </div>
    </section>
    <?php include '../components/footer.php';?>
</body>

</html>
