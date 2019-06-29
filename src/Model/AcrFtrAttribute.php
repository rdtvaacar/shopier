<?php

namespace Acr\Ftr\Model;

use Illuminate\Database\Eloquent\Model;

class AcrFtrAttribute extends Model
{
    protected $connection = 'mysql2';
    protected $table      = 'attributes';

    function attributes()
    {
        return $this->hasMany('Acr\Ftr\Model\AcrFtrAttribute', 'attribute_id', 'id');
    }

    function attribute_create($att_id, $data)
    {
        if (empty($att_id)) {
            return AcrFtrAttribute::insertGetId($data);
        } else {
            AcrFtrAttribute::where('id', $att_id)->update($data);
            return $att_id;
        }

    }

    function sil($data_id)
    {
        if (is_array($data_id)) {
            AcrFtrAttribute::whereIn('id', $data_id)->update(['sil' => 3]);
        } else {
            AcrFtrAttribute::where('id', $data_id)->update(['sil' => 3]);
        }

    }

    function cope_tasi($data_id)
    {
        if (is_array($data_id)) {
            AcrFtrAttribute::whereIn('id', $data_id)->update(['yayin' => 3]);
        } else {
            AcrFtrAttribute::where('id', $data_id)->update(['yayin' => 3]);
        }

    }

    function attribute_multi_create($data)
    {
        return AcrFtrAttribute::insert($data);
    }

    function att_ids_kaydet($product_id, $data)
    {
        if (empty($product_id)) {
            Attribute_product::insert($data);
        } else {
            Attribute_product::where('product_id', $product_id)->delete();
            Attribute_product::insert($data);
        }
    }
}