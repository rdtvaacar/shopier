<?php

namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Bank extends Model

{
    protected $connection = 'mysql';

    /**
     * The database table used by the model.
     *
     * @var string
     */

    function create($bank_id = null, $data)
    {

        if (empty($bank_id)) {
            return Bank::insertGetId($data);
        } else {
            Bank::where('id', $bank_id)->update($data);
            return $bank_id;
        }

    }


}
