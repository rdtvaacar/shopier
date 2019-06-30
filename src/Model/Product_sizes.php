<?php

namespace Acr\Shopier\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Product_sizes extends Model

{
    protected $connection = 'mysql2';
    protected $table      = 'product_size';

    /**
     * The database table used by the model.
     *
     * @var string
     */

    function size()
    {
        return $this->belongsTo('Acr\Shopier\Model\Sizes');
    }

}
