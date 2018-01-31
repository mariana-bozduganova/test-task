<?php

namespace App;

class ProductVariation {

    protected $gender;
    protected $size;
    protected $price;

    public function __construct($gender, $size, $price)
    {
        $this->gender = $gender;
        $this->size = $size;
        $this->price = $price;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getPrice()
    {
        return $this->price;
    }

} 