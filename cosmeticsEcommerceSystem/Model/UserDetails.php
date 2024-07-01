<?php

class UserDetails {
    public $country;
    public $city;
    public $area;
    
    public function __construct($country, $city, $area){
        $this->country = $country;
        $this->city = $city;
        $this->area = $area;
    }
}