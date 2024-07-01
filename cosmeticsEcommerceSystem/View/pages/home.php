<?php
include '../../utils/autoLoading.php';
include '../../utils/ConnectionToDb.php';
include '../../Controller/FeedBackController.php';
include '../../Controller/BannerController.php';
$feedbacks = getAllFeedbacksThatShouldDisplayed();
$banners = getBanners(); 
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
   <title>Beautiflie</title>
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
</head>

<body>
   <?php include '../components/header.php';?>
   <?php include '../components/homePage/banner.php';?>
   <?php include '../components/homePage/about.php'; ?>
   <?php include '../components/homePage/costumerSection.php'; ?>
   <?php if( isset($_SESSION['user']) && !$_SESSION['user']->isAdmin):?>
   <!-- contact section start -->
   <div class="contact_section layout_padding">
      <div class="container">
         <div class="row">
            <div class="col-md-6">
               <h1 class="contact_taital">Get In Touch</h1>
               <p class="contact_text">We're here to help! If you have any questions, feedback, or inquiries, feel free
                  to reach out to us. Our dedicated support team is available to assist you. Please fill out the form
                  below or contact us directly via email or phone. Your satisfaction is our priority.</p>
            </div>
            <div class="col-md-6">
               <div class="contact_main">
                  <div class="contact_bt"><a href="contact.php">Contact Form</a></div>
               </div>
            </div>
         </div>
      </div>
      <?php endif;?>
      <div class="map_main">
         <div class="map-responsive">
            <iframe
               src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA0s1a7phLN0iaD6-UE7m4qP-z21pH0eSc&amp;q=Lebanon+Beirut+Hamra"
               width="600" height="400" frameborder="0" style="border:0; width: 100%;" allowfullscreen=""></iframe>
         </div>
      </div>
   </div>
   <!-- contact section end -->
   <?php include '../components/footer.php';?>
   <!-- Javascript files-->
   <script src="../js/jquery.min.js"></script>
   <script src="../js/popper.min.js"></script>
   <script src="../js/bootstrap.bundle.min.js"></script>
   <script src="../js/jquery-3.0.0.min.js"></script>
   <script src="../js/plugin.js"></script>
   <!-- sidebar -->
   <script src="../js/jquery.mCustomScrollbar.concat.min.js"></script>
   <script src="../js/custom.js"></script>
   <!-- javascript -->
   <script src="../js/owl.carousel.js"></script>
   <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
   <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
</body>

</html>