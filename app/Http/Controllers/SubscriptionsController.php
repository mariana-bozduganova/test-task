<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscription;
use App\ProductVariationsCollection;
use App\Services\SubscriptionsService;
use App\SubscriptionCriteria;
use App\Subscription;
use App\Price;

class SubscriptionsController extends Controller
{
    protected $service;
    protected $productVariations;

    public function __construct(ProductVariationsCollection $productVariations, SubscriptionsService $service)
    {
        $this->service = $service;
        $this->productVariations = $productVariations;
    }

    public function index()
    {
        $data = [
            'sizes' => config('runrepeat.sizes'),
            'prices' => $this->transformPrices()
        ];

        return view('welcome', $data);
    }

    public function store(StoreSubscription $request)
    {
        try {
            $this->service->addSubscription($request->all());
        }
        catch(\Exception $e) {
            session()->flash('error', $e->getMessage());

            return redirect('/');
        }

        session()->flash('message', 'Subscription was successful.');

        return redirect('/');
    }

    protected function transformPrices()
    {
        $formattedPrices = [];

        foreach($this->productVariations as $key => $productVariation) {
            $genderSizeCombinationKey = $productVariation->getGender() . '-' . $productVariation->getSize();
            $formattedPrices[$genderSizeCombinationKey] = Price::displayFormat($productVariation->getPrice());
        }

        return $formattedPrices;
    }

}
