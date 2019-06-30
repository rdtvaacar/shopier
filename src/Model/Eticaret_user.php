<?php

namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;

class Eticaret_user extends Model
{
    protected $connection = 'mysql2';
    protected $table      = 'users';

    function user()
    {
        return $this->hasOne('Acr\Shopier\Model\Eticaret_user', 'id', 'user_id');
    }
}
