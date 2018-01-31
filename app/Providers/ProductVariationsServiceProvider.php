<?php

namespace App\Providers;

use App\ProductVariationsCollection;
use Illuminate\Support\ServiceProvider;

class ProductVariationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProductVariationsCollection::class, function() {
            return ProductVariationsCollection::buildFromConfig(config('runrepeat.variations'));
        });
    }
}
