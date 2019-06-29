<?php

namespace Acr\Ftr\Model;

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
        return $this->belongsToMany('Acr\Ftr\Model\AcrFtrAttribute', 'attribute_product', 'product_id', 'attribute_id');
    }

    function u_kats()
    {
        return $this->belongsToMany('Acr\Ftr\Model\U_kat');
    }

    function my_product()
    {
        return $this->hasOne('Acr\Ftr\Model\Acrproduct');
    }

    function files()
    {
        return $this->hasMany('Acr\Ftr\Model\Product_file', 'acr_file_id', 'acr_file_id');

    }

    function file()
    {
        return $this->hasOne('Acr\Ftr\Model\Product_file', 'acr_file_id', 'acr_file_id');

    }

    function product_kols()
    {
        return $this->hasMany('Acr\Ftr\Model\Product_kols');
    }

    function product_yakas()
    {
        return $this->hasMany('Acr\Ftr\Model\Product_yakas');
    }

    function product_sizes()
    {
        return $this->hasMany('Acr\Ftr\Model\Product_sizes');
    }

    function product_notes()
    {
        return $this->hasMany('Acr\Ftr\Model\Product_note');
    }

    function user_product()
    {
        return $this->hasOne('Acr\Ftr\Model\User_product');
    }
}
