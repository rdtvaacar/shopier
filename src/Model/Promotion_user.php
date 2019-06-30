<?php

namespace Acr\Shopier\Model;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Promotion_user extends Model

{
    protected $connection = 'mysql';
    protected $table      = 'promotion_user';

    function ps()
    {
        return $this->hasOne('Acr\Shopier\Model\Product_sepet', 'id', 'ps_id');
    }

    function promosyon($ps, $user_id)
    {
        $pr_model = new Promotion_user();
        if ($pr_model->where('ps_id', $ps->id)->count() < 1) {
            if ($ps->adet > 1) {
                for ($i = 1; $i < $ps->adet; $i++) {
                    $data[] = [
                        'user_id' => $user_id,
                        'ps_id'   => $ps->id,
                        'code'    => uniqid(rand(100000, 999999))
                    ];
                }
            }
            if (!empty($data)) {
                $pr_model->insert($data);
            }
        }

    }

    function promosyon_user($product_ids, $price, $user_id, $min_ay, $min_adet)
    {
        $promotion_model         = new Promotion();
        $promotion_user_model    = new Promotion_user();
        $promotion_product_model = new Promotion_product();

        $promo_data   = [
            'type'     => 2,
            'price'    => $price,
            'min_ay'   => $min_ay,
            'min_adet' => $min_adet,
            'code'     => uniqid(rand(100000, 999999))
        ];
        $promotion_id = $promotion_model->insertGetId($promo_data);

        $data_user = [
            'user_id'      => $user_id,
            'promotion_id' => $promotion_id,
            'code'         => uniqid(rand(100000, 999999)),
        ];
        $promotion_user_model->insert($data_user);
        foreach ($product_ids as $product_id) {
            $data_product[] = [
                'product_id'   => $product_id,
                'promotion_id' => $promotion_id,
            ];
        }
        $promotion_product_model->insert($data_product);
    }

    function pr_products()
    {
        return $this->hasMany('Acr\Shopier\Model\Promotion_product', 'promotion_id', 'id');
    }

    function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    function promotion()
    {
        return $this->hasOne('Acr\Shopier\Model\Promotion', 'id', 'promotion_id');
    }

}
