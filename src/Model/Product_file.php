<?php

namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Product_file extends Model

{
    protected $connection = 'mysql2';
    protected $table      = 'acr_files_childs';

}
