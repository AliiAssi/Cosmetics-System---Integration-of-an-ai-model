<!-- banner section start -->
<div class="banner_section layout_padding">
         <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
               <?php
               $once = true;
               foreach ($banners as $b) {
               ?>
               <div class="carousel-item <?php if($once) echo 'active'?>">
                  <div class="container">
                     <div class="row mb-5">
                        <div class="col-sm-6">
                           <h1 class="banner_taital">Beauty <br>Kit</h1>
                           <p class="banner_text"><i><b><?=$b->title;?></b></i></p>
                           <div class="read_bt"><a href="products.php">Buy Now</a></div>
                        </div>
                        <div class="col-sm-6">
                           <div class="banner_img"><img src="../images/<?=$b->image;?>" alt="<?=$b->image;?>" height="300px"></div>
                        </div>
                     </div>
                  </div>
               </div>
               <?php
               $once = false;
               }
               ?>
            </div>
         </div>
      </div>
<!-- banner section end -->