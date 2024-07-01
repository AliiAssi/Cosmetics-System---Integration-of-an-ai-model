<?php
include '../../utils/autoLoading.php';
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
    <link href="https://fonts.googleapis.com/css?family=Great+Vibes|Open+Sans:400,700&display=swap&subset=latin-ext" rel="stylesheet">
    <!-- owl stylesheets -->
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/profile.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<!-- product section start -->
<?php include '../components/header.php'; ?>

<body style="background-color: #fef4ec;">

    <!-- starting -->
    <div class="container rounded mb-5 choose-bg">

        <div class="row">
            <div class="col-md-3 border-right">

                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <img class="rounded-circle mt-5" src="../uploaded_img/<?= $_SESSION['user']->profilePicture ?>" alt="<?= $_SESSION['user']->profilePicture ?>"><span class="font-weight-bold"><?= $_SESSION['user']->firstName . " " . $_SESSION['user']->lastName ?>
                    </span><span class="text-black-50"><?= $_SESSION['user']->email ?></span><span>
                    </span>
                </div>
                <?php
                if (isset($_SESSION['d-pp'])) { ?>
                    <div class="alert alert-info"><?= $_SESSION['d-pp'] ?></div>
                <?php
                    unset($_SESSION['d-pp']);
                }
                ?>
                <?php if ($_SESSION['user']->profilePicture != 'default.jpg') {
                ?>
                    <div>
                        <a href="../../routes/userRoute.php?image=delete" class="btn btn-danger ml-4">delete profile picture</a>
                    </div>
                <?php } ?>

                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                    <form action="../../routes/userRoute.php" method="POST" class="picture-form" enctype="multipart/form-data">
                        <?php if (isset($_SESSION['pp-err'])) {
                        ?>
                            <div class="alert alert-danger" style="color:black"><?= $_SESSION['pp-err']; ?></div>
                        <?php
                            unset($_SESSION['pp-err']);
                        } ?>
                        <?php if (isset($_SESSION['pp'])) {
                        ?>
                            <div class="alert alert-success" style="color:black"><?= $_SESSION['pp']; ?></div>
                        <?php
                            unset($_SESSION['pp']);
                        } ?>
                        <input type="file" name="image" id="" class="choose-picture" required>
                        <input type="submit" name="image-updated" value="Save profile picture" class="saving-picture">
                    </form>
                </div>
            </div>
            <div class="col-md-5 border-right">
                <form action="../../routes/userRoute.php" method="POST">
                    <?php
                    if(isset($_SESSION['update-message'])){
                        ?>
                        <div class="alert alert-info text-center"><?=$_SESSION['update-message'];?></div>
                        <?php
                        unset($_SESSION['update-message']);
                    } 
                    ?>
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Profile Settings</h4>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6"><label class="labels">Name</label><input type="text" name="firstName" class="form-control" placeholder="first name" value="<?= $_SESSION['user']->firstName ?>" required></div>
                            <div class="col-md-6"><label class="labels">Surname</label><input type="text" class="form-control" name="lastName" value="<?= $_SESSION['user']->lastName ?>" placeholder="surname" required></div>
                        </div>
                        <div class="row mt-3">

                            <div class="col-md-12"><label class="labels">Gmail</label><input type="text" class="form-control" name="email" placeholder="enter your Gmail" value="<?= $_SESSION['user']->email ?>" required></div>

                        </div>
                        <?php
                        if ($_SESSION['user']->userDetails == null) {
                        ?>
                            <div class="row mt-3">
                                <input type="hidden" name="hidden" value="1">
                                <div class="col-md-6"><label class="labels">Country</label><input name="country" type="text" class="form-control" placeholder="country" value="" required></div>
                                <div class="col-md-6"><label class="labels">City</label><input name="city" type="text" class="form-control" value="" placeholder="state" required></div>
                                <div class="col-md-6"><label class="labels">Area</label><input name="area" type="text" class="form-control" value="" placeholder="state" required></div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class="row mt-3">
                                <input type="hidden" name="hidden" value="0">
                                <div class="col-md-6"><label class="labels">Country</label><input name="country" type="text" class="form-control" placeholder="country" value="<?= $_SESSION['user']->userDetails->country ?>" required></div>
                                <div class="col-md-6"><label class="labels">City</label><input name="city" type="text" class="form-control" value="<?= $_SESSION['user']->userDetails->city ?>" placeholder="state" required></div>
                                <div class="col-md-6"><label class="labels">Area</label><input name="area" type="text" class="form-control" value="<?= $_SESSION['user']->userDetails->area ?>" placeholder="state" required></div>
                            </div>
                        <?php } ?>
                        <div class="mt-5 text-center"><button class="btn btn-primary profile-button" name="updateUserProfile" type="submit">Save
                                Profile</button></div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 py-5">
                <form action="../../routes/userRoute.php" method="POST">
                    <?php if (isset($_SESSION['message'])) {
                    ?>
                        <div class="alert alert-info"><?= $_SESSION['message']; ?></div>
                    <?php
                        unset($_SESSION['message']);
                    } ?>
                    <div class="col-md-12"><label class="labels">old password</label><input type="password" class="form-control" placeholder="****" name="old" value=""></div>

                    <div class="col-md-12"><label class="labels">new password</label><input type="text" class="form-control" placeholder="" name="new" value=""></div>
                    <div class="mt-5 text-center">
                        <button class="btn btn-primary profile-button" name="changePassword" type="submit">Save password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <!-- end -->
    <?php include '../components/footer.php'; ?>
</body>
</html>
<?php
if(isset($_GET['must'])):
?>
<script>
    Swal.fire({
    icon: "warning",
    title: "Oops...",
    text: "if you want to checkout then you must enter your address informations !",
    });
</script>
<?php
endif;
?>