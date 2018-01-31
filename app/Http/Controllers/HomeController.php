<?php

namespace App\Http\Controllers;

use App\Price;
use App\ProductVariationsCollection;
use App\Subscription;
use App\SubscriptionCriteria;

class HomeController extends Controller
{

    protected $productVariations;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductVariationsCollection $productVariations)
    {
        $this->middleware('auth');
        $this->productVariations = $productVariations;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscriptions = auth()->user()->subscriptions()->paginate();
        $subscriptions->getCollection()->transform(function($subscription) {
            return $this->transform($subscription);
        });

        return view('home')->with('subscriptions', $subscriptions);
    }

    protected function transform(Subscription $subscription)
    {
        $productVariation = $this->productVariations->findBySubscriptionCriteria(new SubscriptionCriteria($subscription->gender, $subscription->size));

        return [
            'gender' => $subscription->gender,
            'size' => floatval($subscription->size),
            'email' => $subscription->email,
            'price' => Price::displayFormat($subscription->status == 'sent' ? env('PRICE') : $productVariation->getPrice()),
            'date' => $subscription->created_at->format('d-m-Y H:i:s'),
            'status' => $subscription->status
        ];
    }

}
