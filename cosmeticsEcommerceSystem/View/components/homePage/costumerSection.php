<?php
if(isset($feedbacks) && count($feedbacks) > 0){
?>
<!-- customer section start -->
<div class="customer_section layout_padding">
   <div class="container">
      <div class="row">
         <div class="col-sm-12">
            <h1 class="customer_taital">customer review</h1>
         </div>
      </div>
      <div id="main_slider" class="carousel slide" data-ride="carousel">
         <div class="carousel-inner">
            <?php
            $firstFeedback = true;
            ?>
            <?php
            foreach ($feedbacks as $feedback){ // for i in range(len(feedback))
            ?>
            <div class="carousel-item <?php if ($firstFeedback) {echo 'active';$firstFeedback = false;} ?>"> 
               <div class="client_section_2">
                  <div class="client_main">
                     <div class="client_left">
                        <div class="client_img"><img src="../uploaded_img/<?=$feedback->getFeedBackOwner()->profilePicture?>" alt="<?=$feedback->getFeedBackOwner()->profilePicture?>"></div>
                     </div>
                     <div class="client_right">
                        <h3 class="name_text"><?=$feedback->getFeedBackOwner()->firstName." ".$feedback->getFeedBackOwner()->lastName?></h3>
                        <p class="dolor_text"><?=$feedback->content?> </p>
                     </div>
                  </div>
               </div>
            </div>
            <?php
            }
            ?>

         </div>
         <?php
         if(count($feedbacks) > 1){?>
         <a class="carousel-control-prev" href="#main_slider" role="button" data-slide="prev">
            <i class="fa fa-angle-left"></i>
         </a>
         <a class="carousel-control-next" href="#main_slider" role="button" data-slide="next">
            <i class="fa fa-angle-right"></i>
         </a>
         <?php }
         ?>
      </div>
   </div>
</div>
<!-- customer section end -->
<?php
}
else{ ?>
<div class="customer_section layout_padding">
   <div class="container">
      <div class="row">
         <div class="col-sm-12">
            <div class="alert alert-info text-center" role="alert">No feedbacks yet to display it</div>
         </div>
      </div>
   </div>

</div>
<?php
}
?>