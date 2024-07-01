<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="search-bar">
                    <form action="" method="get">
                        <input type="email" name="email" id="email" placeholder="ENTER AN EMAIL"required
                            class="form-control mb-1">
                        <button type="submit" name="filter" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <?php
                if(isset($_GET['filter'])){
                    $users = searchUserByEmail($_GET['email']);
                }
                else{
                    $users = getAllUsersForAnAdmin(); // Replace with your actual function or logic to fetch users
                }
                foreach ($users as $user) {
                    ?>
            <div class="col-md-4">
                <div class="user-card">
                    <div class="profile-picture">
                        <img src="../uploaded_img/<?= $user->profilePicture; ?>" alt="Profile Picture">
                    </div>
                    <p>Name:<?= $user->firstName." ".$user->lastName; ?></p>
                    <p>Email:<?=$user->email;?></p>
                    <button class="btn btn-primary ml-5" onclick="sing(<?= $user->_id;?>)"><svg
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-eye-fill" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                            <path
                                d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                        </svg></button>
                    <button class="btn btn-danger ml-5" onclick="block(<?= $user->_id;?>)"
                        <?php if($user->getStatus() == 0): ?>
                            style="display: inline"
                        <?php else: ?>
                            style="display: none"
                        <?php endif; ?>
                        id="block<?= $user->_id;?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-ban" viewBox="0 0 16 16">
                            <path
                                d="M15 8a6.97 6.97 0 0 0-1.71-4.584l-9.874 9.875A7 7 0 0 0 15 8M2.71 12.584l9.874-9.875a7 7 0 0 0-9.874 9.874ZM16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0" />
                        </svg></button>
                    <button class="btn btn-success ml-5" onclick="block(<?= $user->_id;?>)"
                        <?php if($user->getStatus() == 1): ?>
                            style="display: inline"
                        <?php else: ?>
                            style="display: none"
                        <?php endif; ?>
                        id="unblock<?= $user->_id;?>"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z" />
                            <path
                                d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466" />
                        </svg></button>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>