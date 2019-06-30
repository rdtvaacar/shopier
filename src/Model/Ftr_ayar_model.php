<?php

namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Auth;

class Ftr_ayar_model extends Model

{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql2';
    protected $table = 'ftr_ayar';


}