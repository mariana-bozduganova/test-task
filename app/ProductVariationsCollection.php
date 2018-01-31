<?php

namespace App;

use Illuminate\Support\Collection;

class ProductVariationsCollection extends Collection {

    /**
     * Creates a collection with the variations information from the config
     *
     * @param array $configVariations
     * @return static
     */
    public static function buildFromConfig(array $configVariations)
    {
        $collection = new static();

        foreach ($configVariations as $genderSizeCombination => $price) {
            list($gender, $size) = explode('-', $genderSizeCombination);
            $collection->push(new ProductVariation($gender, $size, $price));
        }

        return $collection;
    }

    /**
     * Finds the collection item matching the given subscription criteria
     *
     * @param SubscriptionCriteria $subscriptionCriteria
     * @return NullProductVariation
     */
    public function findBySubscriptionCriteria(SubscriptionCriteria $subscriptionCriteria)
    {
        extract($subscriptionCriteria->toArray());

        return $this->findByGenderSizeCombination($gender, $size);
    }

    /**
     * Checks if a collection item matching the given gender/size combination exists
     *
     * @param $gender
     * @param $size
     * @return bool
     */
    public function hasGenderSizeCombination($gender, $size)
    {
        return ! is_a($this->findByGenderSizeCombination($gender, $size), NullProductVariation::class);
    }

    /**
     * Finds the collection item matching the given gender/size combination
     *
     * @param $gender
     * @param $size
     * @return ProductVariation|NullProductVariation
     */
    protected function findByGenderSizeCombination($gender, $size)
    {
        foreach ($this->items as $productVariation) {
            if ($productVariation->getGender() == $gender && $productVariation->getSize() == $size) {
                return $productVariation;
            }
        }

        return new NullProductVariation();
    }

} 