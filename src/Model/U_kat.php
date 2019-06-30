<?php

namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;

class U_kat extends Model
{
    protected $connection = 'mysql2';

    function products()
    {
        return $this->belongsToMany('Acr\Shopier\Model\Product');
    }


    function u_kat()
    {
        return $this->belongsToMany('Acr\Shopier\Model\U_kat', 'parent_id', 'id');
    }

    function u_kats()
    {
        return $this->hasMany('Acr\Shopier\Model\U_kat', 'parent_id', 'id');
    }
}