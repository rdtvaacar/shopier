<?php

namespace Acr\Ftr\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Acrproduct extends Model

{
    protected $connection = 'mysql';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    function attributes()
    {
        return $this->belongsToMany('Acr\Ftr\Model\AcrFtrAttribute', 'attribute_product', 'product_id', 'attribute_id');
    }

    function product()
    {
        return $this->hasOne('Acr\Ftr\Model\Product', 'id', 'product_id');
    }

}
