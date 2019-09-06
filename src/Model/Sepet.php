<?php

namespace Acr\Shopier\Model;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;


class Sepet extends Model
{
    protected $connection = 'mysql';

    /**
     * The database table used by the model.
     *
     * @var string
     */

    function user()
    {
        return $this->belongsTo('App\user');
    }

    function create($session_id = null, $product_id, $data = null, $data_notes = null, $sepet_data = null)
    {

        if ($product_id == 1282) {
            if (Auth::check()) {
                $sepet_id = Sepet::where('siparis', 0)->where('user_id', Auth::user()->id)->first();
                if (!empty($sepet_id)) {
                    Product_sepet::where('product_id', 1282)->where('user_id', Auth::user()->id)->where('sepet_id', $sepet_id)->delete();
                }
            } else {
                $sepet_id = Sepet::where('siparis', 0)->where('session_id', $session_id)->first();
                if (!empty($sepet_id)) {
                    Product_sepet::where('product_id', 1282)->where('user_id', Auth::user()->id)->where('sepet_id', $sepet_id)->delete();
                }

            }
        }
        $sepet_id      = self::product_sepet_id($session_id);
        $product_model = new Product();
        $product       = $product_model->where('id', $product_id)->first();
        if (empty($sepet_id)) {
            if (Auth::check()) {
                $sepet_id = Sepet::insertGetId(['user_id' => Auth::user()->id]);
            } else {
                $sepet_id = Sepet::insertGetId(['session_id' => $session_id]);
            }
        }
        if (!empty($sepet_data)) {
            Sepet::where('id', $sepet_id)->update($sepet_data);
        }
        if (Auth::check()) {
            $data_1     = [
                'product_id' => $product_id,
                'user_id'    => Auth::user()->id,
                'sepet_id'   => $sepet_id,
                'type'       => $product->type
            ];
            $data_merge = array_merge($data_1, $data);
        } else {
            $data_1     = [
                'product_id' => $product_id,
                'sepet_id'   => $sepet_id,
                'type'       => $product->type
            ];
            $data_merge = array_merge($data_1, $data);
        }
        $ps_id = Product_sepet::insert($data_merge);
        if (!empty($data_notes)) {
            $data_notes = array_merge($data_notes[0], ['sepet_id' => $sepet_id]);
            $ps_notes   = new Product_sepet_notes();
            $ps_notes->where('product_id', $product_id)->where('sepet_id', $sepet_id)->delete();
            $ps_notes->insert($data_notes);
        }
        return response()->json([
            'status' => 1,
            'title'  => 'Bilgi',
            'msg'    => 'Ürün başarıyla sepete eklendi.',
            'data'   => $sepet_id
        ]);

    }


    function Acrproducts()
    {
        return $this->belongsToMany('Acr\Shopier\Model\Acrproduct', 'product_sepet', 'sepet_id')->withPivot('adet', 'lisans_ay');
    }

    function products()
    {
        return $this->hasMany('Acr\Shopier\Model\Product_sepet', 'sepet_id', 'id');
    }

    function delete()
    {

    }

    function note()
    {
        return $this->hasOne('Acr\Shopier\Model\Product_sepet_notes', 'sepet_id', 'sepet_id');
    }

    function notes()
    {
        return $this->hasMany('Acr\Shopier\Model\Product_sepet_notes', 'sepet_id', 'id');
    }

    function sepet_birle($session_id)
    {
        Sepet::where('session_id', $session_id)->where('siparis', 0)->update(['user_id' => Auth::user()->id]);
        $sepet_id = $this->product_sepet_id();
        Product_sepet::where('sepet_id', $sepet_id)->update(['user_id' => Auth::user()->id]);
    }

    function product_sepet_id($session_id = null)
    {
        $sepet_model = new Sepet();
        if (Auth::check()) {
            $sepet_sorgu = $sepet_model->where('user_id', Auth::user()->id)->where('siparis', 0);
            if ($sepet_sorgu->count() > 0) {
                $sepet_id = $sepet_sorgu->first()->id;

            } else {
                $sepet_id = 0;
            }
        } else {
            $sepet_sorgu = $sepet_model->where('session_id', $session_id)->where('siparis', 0);
            if ($sepet_sorgu->count() > 0) {
                $sepet_id = $sepet_sorgu->first()->id;

            } else {
                if (empty($session_id)) {
                    $sepet_id = 0;
                } else {
                    $sepet_id = $sepet_model->insertGetId(['session_id' => $session_id]);
                }
            }
        }
        return $sepet_id;
    }

    function product_sepet($session_id = null)
    {
        $sepet_id = self::product_sepet_id($session_id);
        return Product_sepet::where('sepet_id', $sepet_id)->with('product')->get();
    }

    function sepets($session_id = null)
    {

        $sepet_id = self::product_sepet_id($session_id);
        if ($sepet_id == 0) {
            return 0;
        }
        return Product_sepet::where('sepet_id', $sepet_id)->sum('adet');
    }

    function delete_all($session_id = null)
    {
        $sepet_id = self::product_sepet_id($session_id);
        return Product_sepet::where('sepet_id', $sepet_id)->delete();
    }

    function price_update($sepet_id, $total_price)
    {
        Sepet::where('id', $sepet_id)->where('siparis', 0)->update(['price' => $total_price]);
    }

    function adress()
    {
        return $this->hasOne('Acr\Shopier\Model\AcrFtrAdress', 'user_id', 'user_id');
    }
}
