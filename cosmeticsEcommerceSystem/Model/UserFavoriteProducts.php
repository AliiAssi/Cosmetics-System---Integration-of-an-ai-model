<?php
class UserFavoriteProducts {
    public $user_id;
    public $products;

    public function __construct($user_id, $products){
        $this->user_id = $user_id;
        $this->products = $products;
    }
}