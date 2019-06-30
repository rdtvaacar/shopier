<?php

namespace Acr\Shopier\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model

{
    protected $connection = 'mysql';

    function product()
    {
        return $this->hasOne('Acr\Shopier\Model\Product', 'id', 'product_id');
    }

    function prs()
    {
        return $this->hasMany('Acr\Shopier\Model\Promotion_user', 'promotion_id', 'id');
    }
    function pr_products()
    {
        return $this->hasMany('Acr\Shopier\Model\Promotion_product', 'promotion_id', 'id');
    }
}
