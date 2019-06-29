<?php

namespace Acr\Ftr\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class AcrUser extends Authenticatable
{
    use Notifiable;
    protected $connection = 'mysql';
    protected $table      = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    function products()
    {
        return $this->hasMany('Acr\Ftr\Model\Product', 'user_id');
    }

    function sepet()
    {
        return $this->hasOne('Acr\Ftr\Model\Sepet', 'user_id');
    }

    function roles()
    {
        return $this->belongsToMany('Acr\Ftr\Model\AcrRole', 'role_user', 'user_id', 'role_id');
    }

    function adresses()
    {
        return $this->hasMany('Acr\Ftr\Model\AcrMenuAdress', 'user_id');
    }

}
