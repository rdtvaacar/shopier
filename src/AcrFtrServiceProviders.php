<?php

namespace Acr\Ftr;

use Acr\Shopier\Controllers\AcrFtrController;
use Acr\Shopier\Controllers\AcrShopierController;
use Illuminate\Support\ServiceProvider;

class AcrFtrServiceProviders extends ServiceProvider
{
    public function boot()
    {
        include(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/Views', 'acr_shopier');
    }

    public function register()
    {
        $this->app->bind('AcrFtr', function () {
            return new AcrShopierController();
        });
    }
}