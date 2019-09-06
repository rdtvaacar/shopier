<?php

namespace Acr\Shopier\Facades;

use Illuminate\Support\Facades\Facade;

class AcrShopier extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'AcrShopier';
    }

}