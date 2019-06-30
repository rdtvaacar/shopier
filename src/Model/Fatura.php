<?php

namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Fatura extends Model

{
    protected $connection = 'mysql';

    protected $table = 'fatura';

    /**
     * The database table used by the model.
     *
     * @var string
     */

    function user()
    {
        return $this->belongsTo('Acr\Shopier\Model\AcrUser');
    }

    function products()
    {
        return $this->hasMany('Acr\Shopier\Model\Fatura_product', 'order_id', 'order_id');
    }
}
