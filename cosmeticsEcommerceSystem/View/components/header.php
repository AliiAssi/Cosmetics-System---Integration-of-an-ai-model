<?php
session_start();
?>
<!-- header section start -->
<div class="header_section">
    <div class="container-fluid">
        <nav class="navbar navbar-light bg-light justify-content-between">
            <div id="mySidenav" class="sidenav">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                <a href="home.php">Home</a>
                <a href="products.php">Products</a>
                <?php if((isset($_SESSION['user'])&& $_SESSION['user']->isAdmin === 0) || !isset($_SESSION['user'])): ?>
                <a href="contact.php">Feedback</a>
                <a href="about.php">About</a>
                <?php endif; ?>

                <?php if(isset($_SESSION['user'])){ ?>
                    <?php if($_SESSION['user']->isAdmin === 0){ ?>
                        <a href="history.php">History</a>
                    <?php }else{
                        ?>
                        <a href="reviews.php">reviews</a>
                        <?php
                    } ?>
                    <a href="../../routes/authRoute.php?logout=1">Logout</a>
                <?php } ?>

            </div>
            <span class="toggle_icon" onclick="openNav()"><img src="../images/toggle-icon.png"></span>
            <a class="logo" href="home.html"><img src="../images/logo.png"></a></a>
            <?php 
            if(isset($_SESSION['user'])){
            ?>
            <form class="form-inline">
                <div class="d-flex flex-row align-items-center">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item mr-3">
                            <a href="profile.php" class="text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="brown"
                                    class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg>
                            </a>
                        </li>
                        <?php if(!$_SESSION['user']->isAdmin):?>
                        <!-- notification -->
                        <li class="list-inline-item mr-3">
                            <a href="../../routes/productRoute.php?redirect=1" class="text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="brown"
                                    class="bi bi-bookmark-heart" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M8 4.41c1.387-1.425 4.854 1.07 0 4.277C3.146 5.48 6.613 2.986 8 4.412z" />
                                    <path
                                        d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
                                </svg>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="cart.php" class="text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="brown"
                                    class="bi bi-bag" viewBox="0 0 16 16">
                                    <path
                                        d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z" />
                                </svg>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="notification.php" class="text-decoration-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="brown" class="bi bi-bell" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                                </svg>
                            </a>
                        </li>
                        <?php else : ?>
                        <li class="list-inline-item mr-3">
                            <a href="banner.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="brown"
                                    class="bi bi-back" viewBox="0 0 16 16">
                                    <path
                                        d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z" />
                                </svg>
                            </a>
                        </li>
                        <li class="list-inline-item mr-3">
                            <a href="user.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="brown" class="bi bi-people-fill" viewBox="0 0 16 16">
                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                            </svg>
                            </a>
                        </li>
                        <li class="list-inline-item mr-3">
                            <a href="order.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="25" fill="brown"
                                    class="bi bi-list-task" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M2 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-.5-.5zM3 3H2v1h1z" />
                                    <path
                                        d="M5 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M5.5 7a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 4a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1z" />
                                    <path fill-rule="evenodd"
                                        d="M1.5 7a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5zM2 7h1v1H2zm0 3.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm1 .5H2v1h1z" />
                                </svg>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </form>
            <?php
            }else{ ?>
            <div class="form-inline">
                <div class="login_text">
                    <ul>
                        <li>
                            <a href="login.php" style="color:black">login</a>
                        </li>
                    </ul>
                </div>
            </div> <?php }
            ?>

        </nav>
    </div>
</div>
<!-- header section end -->

<head>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "40%";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
        }
    </script>
</head>