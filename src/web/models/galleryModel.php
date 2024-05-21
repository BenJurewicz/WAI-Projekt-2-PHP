<?php

class galleryModel
{
    public $images;
    public $page;
    public $username;

    public function __construct($images, $page, $username)
    {
        $this->images = $images;
        $this->page = $page;
        $this->username = $username;
    }
}
