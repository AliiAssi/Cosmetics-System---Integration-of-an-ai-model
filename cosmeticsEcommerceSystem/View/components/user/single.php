<?php 
$user = getSingleUser($_GET['fetch'])
?>
<section class="ftco-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img src="../uploaded_img/<?= $user->profilePicture; ?>" class="card-img-top"
                        alt="User Profile Picture">
                    <div class="card-body">
                        <p class="card-title">name :<?=$user->getFirstName();?> <?=$user->getLastName();?></p>
                        <p class="card-text">Email: <?=$user->getEmail();?></p>
                        <p class="card-text">address: <?=$user->getAddressAsString()?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-2"><a href="./user.php?fetch=all" class="btn btn-primary"><svg
                xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-backspace-fill" viewBox="0 0 16 16">
                <path
                    d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 2 0 0 0 2-2zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0 1-.707-.708L7.976 8z" />
            </svg></a></div>
</section>