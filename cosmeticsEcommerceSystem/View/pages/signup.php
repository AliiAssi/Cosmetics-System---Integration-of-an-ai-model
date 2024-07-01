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
    <title>Beautiflie Contact Form</title>
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
    <link rel="stylesheet" href="../css/login.css">
</head>

<body style="background-color: #fef4ec;">
    <?php include '../components/header.php';?>
    <div class="limit">
        <div class="login-container">
            <div class="bb-login mb-0 mt-0">
                <form class="bb-form validate-form" action="../../routes/authRoute.php" method="post"> <span class="bb-form-title p-b-26"> SIGN UP </span> <span
                        class="bb-form-title p-b-48"> <i class="mdi mdi-symfony"></i> </span>
                    <?php if(isset($_SESSION['message'])) {
                    ?>
                        <div class="alert alert-info"><?=$_SESSION['message'];?></div>
                    <?php
                    unset($_SESSION['message']);    
                    } ?>
                    <div class="wrap-input100 validate-input"> <input class="input100" type="text" name="firstName" required>
                        <span class="bbb-input" data-placeholder="First name"></span> </div>
                    <div class="wrap-input100 validate-input"> <input class="input100" type="text" name="lastName" required>
                        <span class="bbb-input" data-placeholder="Last name"></span> </div>
                    <div class="wrap-input100 validate-input" data-validate="Valid email is: a@b.c"> <input
                            class="input100" type="email" name="email" required> <span class="bbb-input"
                            data-placeholder="Email"></span> </div>
                    <div class="wrap-input100 validate-input" data-validate="Enter password"> <span
                            class="btn-show-pass"> <i class="mdi mdi-eye show_password"></i> </span> <input
                            class="input100" type="password" name="password" required> <span class="bbb-input"
                            data-placeholder="Password"></span> </div>
                    <div class="login-container-form-btn">
                        <div class="bb-login-form-btn">
                            <div class="bb-form-bgbtn"></div> <button class="bb-form-btn" name="auth" value="signup"> SIGN UP </button>
                        </div>
                    </div>
                    <div class="text-center p-t-115"> <span class="txt1"> alread have an account? </span> <a
                            class="txt2" href="login.php"> Sign IN </a> </div>
                </form>
            </div>
        </div>
    </div>
    <?php include '../components/footer.php';?>
</body>

</html>