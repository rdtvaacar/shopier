<?php

namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;

class Product_u_kat extends Model
{
    protected $connection = 'mysql2';
    protected $table      = 'product_u_kat';

    public function u_kat()
    {
        return $this->hasOne('Acr\Shopier\Model\U_kat', 'id', 'u_kat_id');
    }

    function product()
    {
        return $this->belongsTo('Acr\Shopier\Model\Product', 'id', 'product_id');
    }
}