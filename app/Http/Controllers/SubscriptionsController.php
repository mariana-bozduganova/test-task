<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscription;
use App\ProductVariationsCollection;
use App\SubscriptionCriteria;
use App\Subscription;
use App\Price;

class SubscriptionsController extends Controller
{
    protected $productVariations;

    public function __construct(ProductVariationsCollection $productVariations)
    {
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
            Subscription::withCriteria(new SubscriptionCriteria($request->get('gender'), $request->get('size')))->make($request->get('email'));
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
