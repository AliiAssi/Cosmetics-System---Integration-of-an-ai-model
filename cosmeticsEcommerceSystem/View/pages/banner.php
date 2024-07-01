<?php
include '../../utils/autoLoading.php';
include '../../utils/ConnectionToDb.php';
include '../../Controller/BannerController.php';
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <style>
        .card-img-top {
            max-width: 100%;
            /* Maximum width */
            max-height: 200px;
            /* Maximum height */
            object-fit: cover;
            /* Ensure the image covers the entire space */
        }

        .none {
            display: none;
        }
    </style>
</head>
<!-- product section start -->
<?php include '../components/header.php'; ?>
<?php
if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']->isAdmin !== 1)) {
    header("Location:login.php");
}
?>

<body>
    <div class="about_section layout_padding">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <a href="#" class="btn btn-primary" onclick="creationForm()" id="boutton">Create One</a>
                    </div>
                </div>

            </div>
            <div class="col-md-6 none" id="form">
                <div class="p-4">
                    <form action="../../routes/bannerRoute.php" class="needs-validation" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image" required>
                                <label class="custom-file-label" for="image">Choose file</label>
                            </div>
                        </div>

                        <button name="insert" type="submit" class="btn btn-primary">Submit</button>
                        <a href="banner.php" class="btn btn-danger">back</a>
                    </form>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <?php foreach ($banners as $banner) : ?>
                    <div class="col-md-4 mt-3" id="banner<?= $banner->_id ?>">
                        <div class="card">
                            <?php if (!isset($_GET['id'])) : ?>
                                <img src="../images/<?= $banner->image ?>" class="card-img-top" alt="Image 1">
                                <div class="card-body">
                                    <p class="card-text"><?= $banner->title; ?></p>
                                    <a href="banner.php?id=<?= $banner->_id ?>" style="color: white;" class="btn btn-primary">EDIT</a>
                                    <a style="color: white;" class="btn btn-danger" onclick="del('<?= $banner->_id ?>')">DELETE</a>
                                </div>
                            <?php else : ?>
                                <?php if($_GET['id']  === $banner->_id): ?>
                                    <div class="p-4">
                                    <form action="../../routes/bannerRoute.php" class="needs-validation" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= $banner->_id; ?>">

                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input type="text" class="form-control" id="title" name="title" value="<?= $banner->title; ?>" required>
                                            <div class="invalid-feedback">Please provide a title.</div>
                                        </div>

                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="image" name="image">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                            </div>
                                        </div>

                                        <button name="edit" type="submit" class="btn btn-primary">Submit</button>
                                        <a href="banner.php" class="btn btn-danger">back</a>
                                    </form>
                                </div>
                                <?php else : ?>
                                    <img src="../images/<?= $banner->image ?>" class="card-img-top" alt="Image 1">
                                    <div class="card-body">
                                        <p class="card-text"><?= $banner->title; ?></p>
                                        <a href="banner.php?id=<?= $banner->_id ?>" style="color: white;" class="btn btn-primary">EDIT</a>
                                        <a style="color: white;" class="btn btn-danger" onclick="del('<?= $banner->_id ?>')">DELETE</a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
    <?php include '../components/footer.php'; ?>
</body>

</html>
<script>
function creationForm(){
    const clicked  = document.getElementById('boutton');
    if (clicked){
        clicked.remove();
    }
    const form = document.getElementById('form');
    if (form){
        form.classList.remove('none');
    }
}
</script>
<script src="../js/banner.js"></script>
<?php if (isset($_SESSION['edit'])) : ?>
    <script>
        <?php
        if ($_SESSION['edit'] === true) : ?>
            Toastify({
                text: "EDITED",
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                }
            }).showToast();
        <?php else : ?>
            Toastify({
                text: "ERROR OCCURED",
                duration: 3000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                }
            }).showToast();
        <?php endif; ?>
    </script>
<?php
    unset($_SESSION['edit']);
endif; ?>