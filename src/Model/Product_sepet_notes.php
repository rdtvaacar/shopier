<?php

namespace Acr\Ftr\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Product_sepet_notes extends Model

{
    protected $table      = 'product_sepet_notes';

    /**
     * The database table used by the model.
     *
     * @var string
     */

    function note()
    {
        return $this->belongsTo('Acr\Ftr\Model\Product_note');
    }

}
