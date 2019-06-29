<?php

namespace Acr\Ftr\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;

class User_product extends Model

{
    protected $connection = 'mysql2';
    protected $table      = 'user_product';
    function user()
    {
        return $this->hasOne('Acr\Ftr\Model\Eticaret_user','id','user_id');
    }
}
