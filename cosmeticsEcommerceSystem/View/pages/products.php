<?php
include '../../utils/autoLoading.php';
include '../../utils/ConnectionToDb.php';
include '../../Controller/ProductController.php';
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
    <link rel="stylesheet" href="../css/products.css">
    <script>
    function scrollToDiv() {
        // Get the selected value from the select element
        var selectedCategory = document.getElementById("categorySelect").value;
        // Scroll to the div with the corresponding ID
        var element = document.getElementById(selectedCategory);//makeup
        if (element) {
            console.log(element);
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
    </script>
    <style>
    .category_title{
        font-size: 2rem;
        color: #4e4e4e; /* Dark gray color for a professional look */
        font-weight: bold;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 20px;
        /* Additional styling to make it stand out */
        border-bottom: 3px solid #ccc; /* Gray underline */
        padding-bottom: 10px; /* Space between title and underline */
    }
    .title {
    padding: 15px; /* Increased padding for a more luxurious feel */
    background-color: #ffe6ea; /* Soft and elegant background color */
    border-top: 3px solid #ff99cc; /* Delicate border to frame the title (top only) */
    border-right: 3px solid #ff99cc; /* Delicate border to frame the title (right only) */
    border-left: 3px solid #ff99cc; /* Delicate border to frame the title (left only) */    border-radius: 10px; /* Rounded corners for a smoother appearance */
    font-size: 1.3rem; /* Slightly larger font size for emphasis */
    font-weight: bold; /* Bold font weight for prominence */
    text-transform: uppercase; /* Uppercase text for sophistication */
    color: #4e4e4e; /* Subdued color for a professional look */
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
    }
    .onHover:hover{
        cursor:pointer;
    }

    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<!-- product section start -->
<?php include '../components/header.php';?>
<?php
if(isset($_SESSION['user'])){
    include '../../Controller/UserFavoriteProductsController.php';
}
if(isset($_GET['fav'])){
    if(!isset($_SESSION['user'])){
       ?>
       <script>
        window.location.href = "login.php";
       </script>
       <?php
    }
    $products = getUserFavorites($_SESSION['user']->_id);
}
else{
    if(isset($_SESSION['user']) && $_SESSION['user']->isAdmin === 1){
        $products = getAllProductsForAdmin();
    }else{
    $products = getProducts();
    }
}
$allCategories = [];
/**
 * makeup [] , skincare [] 
 */
foreach ($products as $key => $productArray){
    $allCategories [] = $key;  
}

?>

<div class="product_section layout_padding" style="background-color: #fef4ec;">
    <?php if (isset($_SESSION['user']) && $_SESSION['user']->isAdmin) : ?>
        <div class="container">
            <button type="button" class="btn btn-primary" onclick="r()">
                ADD A PRODUCT <i class="fa fa-plus"></i>
            </button>
        </div>
    <?php endif; ?>
    <div class="container">
        <?php if(count($allCategories) >= 1): ?>
            <div class="fixed-div">
            <div class="select-input">
                <label for="categorySelect">select your target category</label>
                <select name="category" id="categorySelect" onchange="scrollToDiv()">
                    <option value="">select a category</option>
                    <?php foreach($allCategories as $cat): ?>
                        <option value="<?=$cat?>"><?=$cat?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <?php endif; ?>
        <?php if (!isset($products) || count($products) < 1 ): ?>
        <div class="alert alert-danger mt-5 text-center">
            <strong>Alert!</strong>
            <?php if (!isset($_GET['fav'])) : ?>
                <p>Dear valued customer, we apologize, but currently there are no products available. Please check back
                later for updates. Thank you for your understanding.</p>
            <?php else: ?>
                <p>
                    currently there are no products in the FAVORITES
                </p>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <?php foreach ($products as $key => $productArray): ?>
        <!-- category start -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="category_title" id="<?=$key?>"><span class="title"><?=$key?></span></h1>
            </div>
        </div>
        <div class="product_section_2 layout_padding">
            <div class="row">
                <?php
                    foreach ($productArray as $product) :
                    ?>
                <div class="col-lg-3 col-sm-6">
                    <div class="product_box" id="delete<?=$product->getId();?>">
                        <h4 class="bursh_text"><?=$product->getName();?></h4>
                        <p class="lorem_text"><?=$product->getDescription();?></p>
                        <img src="../uploaded_img/<?=$product->getPicture()?>" class="image_1">
                        <?php
                        if(!isset($_SESSION['user'])):
                        ?>
                        <div class="alert alert-danger text-center mr-2 ml-2">for shopping u must <a href="login.php" class="btn">login</a></div>
                        <?php
                        else:
                        ?>
                        <div class="btn_main">
                            <div class="buy_bt">
                                <?php if(!$_SESSION['user']->isAdmin): ?>
                                    <ul>
                                    <?php if(isFavorite($_SESSION['user']->_id, $product->getId())): ?>
                                    <li><a 
                                        onclick="like(<?=$_SESSION['user']->_id?>, <?=$product->getId()?>, 0)"
                                        class="onHover"
                                        ><svg id="like<?=$product->getId();?>" class="mt-2" xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="red" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                                        </svg>
                                        </a>
                                    </li>
                                    <?php else: ?>
                                    <li>
                                        <a 
                                        onclick="like(<?=$_SESSION['user']->_id?>, <?=$product->getId()?>, 1)"
                                        class="onHover"
                                        ><svg id="like<?=$product->getId();?>" class="mt-2" xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="white" stroke="red" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
                                        </svg>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    <li><a
                                    id="sweet<?=$product->getId()?>"
                                    onclick="addToCart(<?=$product->getId()?>)"
                                    class="onHover"
                                    >
                                        <svg class="mt-1" xmlns="http://www.w3.org/2000/svg" width="20" height="16" fill="green" class="bi bi-bag-plus" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5"/>
                                            <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
                                        </svg></a>
                                    </li>
                                </ul>
                                <?php else : ?>
                                    <ul>
                                        <li>
                                        <a style="background-color:#ff99cc;color:white" href="./manipulateProduct.php?id=<?=$product->getId()?>">edit</a>
                                        <a style="background-color:red;color:aliceblue" onclick="deleteProduct(<?=$product->getId()?>)">delete</a>
                                        </li>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif;?>
                        <div class="btn_main" style="color:white;">
                            <div class="buy_bt">
                                <ul>
                                    <?php if (isset($_SESSION['user']) && $_SESSION['user']->isAdmin) : ?>
                                    <li>
                                        <span style="color:aquamarine">
                                        nb of items : <?=$product->getProductCount(); ?>
                                        </span>
                                    </li>
                                    <?php endif; ?>
                                    <li><span style="color: red;"><del><?=$product->getPrice()?>
                                                USD</del></span></li>
                                    <li>Discount: <span style="color:red"><?=$product->getDiscount()?>%</span></li>
                                    <li style="color:white">
                                        <?= $product->getPrice() - ($product->getPrice() * $product->getDiscount() / 100) ?>
                                        USD</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php
        endif; 
        ?>

    </div>
</div>
<?php include '../components/footer.php'; ?>

<body>
</body>
</html>
<script src="../js/products.js"></script>

<?php if(isset($_GET['fav'])  && isset($_SESSION['redirected']) && count($products) >= 1):?>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        Toastify({
        text: "YOUR FAVORITES",
        duration: 5000,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "center", // `left`, `center` or `right`
        style: {
            background: "linear-gradient(to right, #00b09b, #96c93d)",
        }

        }).showToast();
    </script>
<?php
unset($_SESSION['redirected']);
endif;
?>
<?php if(isset($_GET['message'])):?>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        Toastify({
        text: "you have no orders yet",
        duration: 3000,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "center", // `left`, `center` or `right`
        style: {
            background: "linear-gradient(to right, #00b09b, #96c93d)",
        }
        }).showToast();
    </script>
<?php
endif;
?>

<script>
    function r(){
        window.location.href = "./addProduct.php";
    }
</script>