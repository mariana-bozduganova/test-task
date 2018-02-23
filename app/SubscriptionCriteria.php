<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;

class SubscriptionCriteria implements Arrayable {

    protected $gender;
    protected $size;

    public function __construct($gender, $size)
    {
        if ( ! $this->isValidCombination($gender, $size)) {
            throw new \Exception('The subscription criteria is not valid');
        }

        $this->gender = $gender;
        $this->size = $size;
    }

    public function gender()
    {
        return $this->gender;
    }

    public function size()
    {
        return $this->size;
    }

    public function toArray()
    {
        return [
            'gender' => $this->gender,
            'size' => $this->size
        ];
    }

    protected function isValidCombination($gender, $size)
    {
        return app(ProductVariationsCollection::class)->hasGenderSizeCombination($gender, $size);
    }

} 