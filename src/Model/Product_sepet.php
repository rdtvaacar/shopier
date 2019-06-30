<?php

namespace Acr\Shopier\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

class Product_sepet extends Model

{
    protected $connection = 'mysql';
    protected $table      = 'product_sepet';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    function product()
    {
        return $this->hasOne('Acr\Shopier\Model\Product', 'id', 'product_id');
    }

    function acr_product()
    {
        return $this->hasOne('Acr\Shopier\Model\Acrproduct', 'product_id', 'product_id');
    }

    function sepet()
    {
        return $this->hasOne('Acr\Shopier\Model\Sepet', 'id', 'sepet_id');
    }

    function use_plus($product_id, $sepet_id, $data = null, $data_notes = null)
    {
        $sorgu    = Product_sepet::where('product_id', $product_id)->where('sepet_id', $sepet_id);
        $satir    = $sorgu->first();
        $ps_notes = new Product_sepet_notes();
        if (!empty($data_notes)) {
            $ps_notes->where('product_id', $satir->product_id)->where('sepet_id', $satir->sepet_id)->delete();
            $ps_notes->insert($data_notes);
        }
        $data_1     = ['adet' => $satir->adet + 1];
        $data_merge = array_merge($data_1, $data);
        $sorgu->update($data_merge);
    }

    function product_notes()
    {
        return $this->hasMany('Acr\Shopier\Model\Product_note', 'product_id');
    }

    function size()
    {
        return $this->belongsTo('Acr\Shopier\Model\Sizes');
    }

    function yaka()
    {
        return $this->belongsTo('Acr\Shopier\Model\Yakas');
    }

    function kol()
    {
        return $this->belongsTo('Acr\Shopier\Model\Kols');
    }

    function notes()
    {
        return $this->hasMany('Acr\Shopier\Model\Product_sepet_notes', 'product_id', 'product_id');
    }
}
