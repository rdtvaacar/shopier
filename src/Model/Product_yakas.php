<?php

namespace Acr\Shopier\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Product_yakas extends Model

{
    protected $connection = 'mysql2';
    protected $table      = 'product_yaka';

    /**
     * The database table used by the model.
     *
     * @var string
     */

    function yaka()
    {
        return $this->belongsTo('Acr\Shopier\Model\Yakas');
    }

}
