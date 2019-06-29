<?php

namespace Acr\Ftr\Model;

use Illuminate\Database\Eloquent\Model;

class U_kat extends Model
{
    protected $connection = 'mysql2';

    function products()
    {
        return $this->belongsToMany('Acr\Ftr\Model\Product');
    }


    function u_kat()
    {
        return $this->belongsToMany('Acr\Ftr\Model\U_kat', 'parent_id', 'id');
    }

    function u_kats()
    {
        return $this->hasMany('Acr\Ftr\Model\U_kat', 'parent_id', 'id');
    }
}