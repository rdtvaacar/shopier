<?php

namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Product extends Model

{
    protected $connection = 'mysql2';

    /**
     * The database table used by the model.
     *
     * @var string
     */

    function attributes()
    {
        return $this->belongsToMany('Acr\Shopier\Model\AcrFtrAttribute', 'attribute_product', 'product_id', 'attribute_id');
    }

    function u_kats()
    {
        return $this->belongsToMany('Acr\Shopier\Model\U_kat');
    }

    function my_product()
    {
        return $this->hasOne('Acr\Shopier\Model\Acrproduct');
    }

    function files()
    {
        return $this->hasMany('Acr\Shopier\Model\Product_file', 'acr_file_id', 'acr_file_id');

    }

    function file()
    {
        return $this->hasOne('Acr\Shopier\Model\Product_file', 'acr_file_id', 'acr_file_id');

    }

    function product_kols()
    {
        return $this->hasMany('Acr\Shopier\Model\Product_kols');
    }

    function product_yakas()
    {
        return $this->hasMany('Acr\Shopier\Model\Product_yakas');
    }

    function product_sizes()
    {
        return $this->hasMany('Acr\Shopier\Model\Product_sizes');
    }

    function product_notes()
    {
        return $this->hasMany('Acr\Shopier\Model\Product_note');
    }

    function user_product()
    {
        return $this->hasOne('Acr\Shopier\Model\User_product');
    }
}
