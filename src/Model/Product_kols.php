<?php

namespace Acr\Shopier\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Product_kols extends Model

{
    protected $connection = 'mysql2';
    protected $table      = 'product_kol';

    /**
     * The database table used by the model.
     *
     * @var string
     */

    function kol()
    {
        return $this->belongsTo('Acr\Shopier\Model\Kols');
    }

}
