<?php
class Banner {
    public $_id;
    public $title;
    public $image;
    public function __construct($id, $title, $image){
        $this->_id = $id;
        $this->title = $title;
        $this->image = $image;
    }
}