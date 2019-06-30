<?php

namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Auth;
use DB;

class AcrRole extends Model

{
    protected $table = 'roles';

    protected $connection = 'mysql';
    /**
     * The database table used by the model.
     *
     * @var string
     */
}
