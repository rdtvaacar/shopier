<?php

namespace Acr\Ftr\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model

{
    protected $connection = 'mysql';

    function product()
    {
        return $this->hasOne('Acr\Ftr\Model\Product', 'id', 'product_id');
    }

    function prs()
    {
        return $this->hasMany('Acr\Ftr\Model\Promotion_user', 'promotion_id', 'id');
    }
    function pr_products()
    {
        return $this->hasMany('Acr\Ftr\Model\Promotion_product', 'promotion_id', 'id');
    }
}
