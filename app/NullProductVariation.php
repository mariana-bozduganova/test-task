<?php

namespace App;

class NullProductVariation {

    public function __construct()
    {
    }

    public function getGender()
    {
        return null;
    }

    public function getSize()
    {
        return null;
    }

    public function getPrice()
    {
        return 0;
    }
} 