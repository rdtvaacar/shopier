<?php

namespace Acr\Shopier\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Promotion_product extends Model

{
    protected $connection = 'mysql';
    protected $table      = 'promotion_product';

    function product()
    {
        return $this->hasOne('Acr\Shopier\Model\Product', 'id', 'product_id');
    }
}
