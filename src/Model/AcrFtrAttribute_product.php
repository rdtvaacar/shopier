<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcrFtrAttribute_product extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'attribute_product';


    public function att()
    {
        return $this->hasOne('App\Attribute', 'id', 'attribute_id');
    }

}