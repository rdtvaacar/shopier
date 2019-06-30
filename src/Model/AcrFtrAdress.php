<?php


namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class AcrFtrAdress extends Model
{
    protected $connection = 'mysql';
    protected $table      = 'adresses';

    function create($adress_id = null, $data)
    {
        AcrFtrAdress::where('user_id', Auth::user()->id)->update(['active' => 2]);
        if (empty($adress_id)) {
            $adress_id = AcrFtrAdress::insertGetId($data);
            return $adress_id;
        } else {
            AcrFtrAdress::where('id', $adress_id)->update($data);
            return $adress_id;
        }

    }

    function city()
    {
        return $this->belongsTo('Acr\Shopier\Model\City', 'city_id', 'id');
    }

    function county()
    {
        return $this->belongsTo('Acr\Shopier\Model\County', 'county_id', 'id');
    }

    function active_adress($adress_id)
    {
        AcrFtrAdress::where('user_id', Auth::user()->id)->update(['active' => 2]);
        AcrFtrAdress::where('id', $adress_id)->update(['active' => 1]);
    }

}