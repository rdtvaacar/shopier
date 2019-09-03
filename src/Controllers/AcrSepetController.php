<?php

namespace Acr\Shopier\Controllers;

use Acr\Shopier\Model\Acr_user_table_conf;
use Acr\Shopier\Model\AcrFtrAdress;
use Acr\Shopier\Model\AcrFtrIyzico;
use Acr\Shopier\Model\AcrUser;
use Acr\Shopier\Model\Bank;
use Acr\Shopier\Model\City;
use Acr\Shopier\Model\Company_conf;
use Acr\Shopier\Model\County;
use Acr\Shopier\Model\Fatura;
use Acr\Shopier\Model\Fatura_product;
use Acr\Shopier\Model\Product;
use Acr\Shopier\Model\Product_sepet;
use Acr\Shopier\Model\Product_sepet_notes;
use Acr\Shopier\Model\Promotion;
use Acr\Shopier\Model\Promotion_user;
use Acr\Shopier\Model\Sepet;
use AcrMenu;
use App\Handlers\Commands\my;
use App\Http\Controllers\MarketController;
use Auth;
use Redirect;
use Session;
use Shopier\Enums\ProductType;
use Shopier\Exceptions\NotRendererClassException;
use Shopier\Exceptions\RendererClassNotFoundException;
use Shopier\Exceptions\RequiredParameterException;
use Shopier\Models\Address;
use Shopier\Models\Buyer;
use Shopier\Renderers\AutoSubmitFormRenderer;
use Shopier\Renderers\ButtonRenderer;
use Shopier\Shopier;
use Validator;
use Illuminate\Http\Request;
use App\User;

class AcrSepetController extends Controller
{
    protected $config_name;
    protected $config_user_name;
    protected $config_email;
    protected $config_lisans_durum;
    protected $config_lisans_baslangic;
    protected $config_lisans_bitis;

    function __construct()
    {
        $conf_table_model   = new Acr_user_table_conf();
        $conf_table         = $conf_table_model->first();
        $this->config_name  = $conf_table->name;
        $this->config_email = $conf_table->email;
    }


    function index()
    {
        $user_model = new AcrUser();
        $sepets     = $user_model->find(Auth::user()->id)->sepets()->get();
        return View('acr_shopier::anasayfa');
    }

    function lisans_urun_sepete_ekle(Request $request)
    {
        $urun_ids = explode("_", $request->product_ids);
        $adet     = $request->adet;
        $ay       = $request->ay;
        array_pop($urun_ids);
        $sepet      = new Sepet();
        $fiyat_data = $this->lisans_urun_fiyat_hesapla($request, $adet, $ay, $request->product_ids);
        $dis_rate   = number_format(str_replace('%', '', $fiyat_data[2])) / 100;
        $sepet_data = [
            'dis_rate' => $dis_rate,
        ];
        $ps_data    = [
            'adet'        => $adet,
            'product_ids' => json_encode($urun_ids),
            'price'       => $fiyat_data[0],
            'lisans_ay'   => $ay,
            'dis_rate'    => $dis_rate,
        ];
        if (empty($request->session()->get('session_id'))) {
            $session_id = rand(1000000, 99999999);
            session()->put('session_id', $session_id);
        } else {
            $session_id = session()->get('session_id');
        }
        $result = $sepet->create($session_id, 1282, $ps_data, null, $sepet_data);
        if (!empty($request->usb_note)) {
            $veri     = (json_encode($result));
            $cam      = json_decode($veri);
            $sepet_id = $cam->original->data;
            Product_sepet_notes::insert([
                'sepet_id'   => $sepet_id,
                'name'       => $request->usb_note,
                'product_id' => 10
            ]);
        }

    }

    function lisans_urun_fiyat_hesapla(Request $request, $adet = null, $ay = null, $urun_id_data = null)
    {
        $urun_id_data = empty($urun_id_data) ? $request->product_ids : $urun_id_data;
        $urun_ids     = explode("_", $urun_id_data);
        $adet         = empty($adet) ? $request->adet : $adet;
        $ay           = empty($ay) ? $request->ay : $ay;
        array_pop($urun_ids);
        $model  = new Product();
        $lisans = $model->where('id', 1282)->first();

        if (in_array(1282, $urun_ids)) {
            $lisans_fiyat = self::price_set_lisans($lisans, 0, $adet, $ay);
        }
        $fiyat   = [];
        $key_usb = array_search(10, $urun_ids);
        unset($urun_ids[0]);
        if (!empty($key_usb)) {
            $fiyat_usb = 100;
            unset($urun_ids[$key_usb]);
        } else {
            $fiyat_usb = 0;
        }
        switch (count($urun_ids)) {
            case 1:
                $fiyat[] = 2.2798;
                break;
            case 2:
                $fiyat[] = 3.016;
                break;
            case 3:
                $fiyat[] = 3.3841;
                break;
            case 4:
                $fiyat[] = 3.752;
                break;
            case 5:
                $fiyat[] = 5.959;
                break;
        }
        $kat_sayi   = 1.359;
        $data_fiyat = self::price_set_lisans($lisans, 0, 1, 1) * $adet * $ay;
        $sum_fiyat  = array_sum($fiyat) * $adet * $ay * $kat_sayi + $fiyat_usb;
        $fiyat      = round($sum_fiyat + $lisans_fiyat, 2);
        $dis_rate   = round($this->dis_rate(($data_fiyat) + $sum_fiyat, $fiyat), 2);
        $indirimsiz = round($fiyat * (1 + $dis_rate), 2);
        $dis_rate   = '%' . $dis_rate * 100;
        $kisi_basi  = round($fiyat / $adet, 2);
        return [
            $fiyat,
            $indirimsiz,
            $dis_rate,
            $kisi_basi
        ];
    }

    function lisans_urunleri()
    {
        $model    = new Product();
        $products = $model->whereIn('id', [
            1282,
            1283,
            1284,
            1285,
            1286,
            1287,
            10
        ])->get();
        foreach ($products as $product) {
            $urun[$product->id] = $product;
        }
        $product_ids = [
            1282,
            1283,
            1284,
            1285,
            1286,
            1287,
            10
        ];
        return view('acr_shopier::product_lisans', compact('urun', 'product_ids'));
    }

    function indirim()
    {
        $promotion_user_model = new Promotion_user();
        $created_at           = date('Y-m-d', strtotime("+1 years"));
        $promotion_users      = $promotion_user_model->where('user_id', @Auth::user()->id)->whereDate('created_at', '>', $created_at)->with([
            'promotion' => function ($q) {
                $q->with([
                    'pr_products' => function ($q) {
                        $q->with('product');
                        $q->whereDate('created_at', '>', $created_at);

                    }
                ]);
                $q->whereDate('created_at', '>', $created_at);
            },
            'ps'        => function ($q) {
                $q->with('product');
                $q->whereDate('created_at', '>', $created_at);

            },
        ])->get();
        $promo_user_ids       = [];
        $promo_user           = [];
        foreach ($promotion_users as $promotion_user) {
            if (!empty($promotion_user->promotion->pr_products)) {
                foreach ($promotion_user->promotion->pr_products as $item) {
                    $promo_user[$item->product_id] = $promotion_user->promotion;
                    $promo_user_ids[]              = $item->product_id;
                }
            }
        }
        return [
            $promo_user,
            $promo_user_ids
        ];
    }

    function admin_orders_cleaner()
    { // ödenmeyen ve aktif edilmeyen siparişleri sistemden tamamen siler.
        $sepet_mdel     = new Sepet();
        $pr_sepet_model = new Product_sepet();
        $sepets         = $sepet_mdel->where('active', 0)->where('order_result', 1)->get();
        foreach ($sepets as $sepet) {
            $sepet_ids[] = $sepet->id;
        }
        $pr_sepet_model->whereIn('sepet_id', $sepet_ids)->delete();
        $sepet_mdel->whereIn('id', $sepet_ids)->delete();
        return redirect()->back()->with('msg', $this->basarili());
    }

    function promotion_code_active(Request $request)
    {
        $market_controller = new MarketController();
        $code              = $request->code;
        $pr_model          = new Promotion_user();
        $sayi              = $pr_model->where('code', $code)->where('active', 1)->count();
        preg_match('/product/', $code, $deger);
        if (count($deger) > 0) {
            $promo_model = new Promotion();
            $pr          = $promo_model->where('code', $code)->first();
            if ($pr->son <= $pr->ilk) {
                return redirect()->back()->with('msg', $this->uyariMsj('Bu promosyon kodu kullanım limitine ulaşmıştır, ilginize teşekkür ederiz.'));
            }
            if (strtotime($pr->last_date) < time()) {
                return redirect()->back()->with('msg', $this->uyariMsj('Bu promosyon kodu süresi bitmiştir son tarihi ' . date('d/m/Y', strtotime($pr->last_date)) . ', ilginize teşekkür ederiz.'));
            }
            self::create($request, $pr->product_id);
            return redirect()->to('/acr/ftr/card/sepet');
        }
        if ($sayi < 1) {
            return redirect()->back()->with('msg', $this->uyariMsj('Pormosyon Kodu Geçersizdir!!!'));
        }
        $pr = $pr_model->where('code', $code)->with([
            'ps'
        ])->first();
        $pr_model->where('code', $code)->update([
            'active'         => 2,
            'active_user_id' => Auth::user()->id
        ]);
        return $market_controller->order_result($request, $pr->ps->sepet_id, [$pr->ps->product_id], Auth::user()->id);
    }

    function order_cancel(Request $request, $order_id = null)
    {
        $order_id             = empty($order_id) ? $request->input('order_id') : $order_id;
        $sepet_model          = new Sepet();
        $sepet                = $sepet_model->find($order_id);
        $sepet->fatura_active = $request->order_active;
        $sepet->save();
    }

    function order_fatura_active(Request $request)
    {
        $order_id             = empty($order_id) ? $request->input('order_id') : $order_id;
        $sepet_model          = new Sepet();
        $sepet                = $sepet_model->find($order_id);
        $sepet->fatura_active = $sepet->fatura_active == 1 ? 2 : 1;
        $sepet->save();
    }

    function orders(Request $request)
    {
        $sepet_model = new Sepet();
        $orders      = $sepet_model->where('user_id', Auth::user()->id)->where('siparis', 1)->orderBy('id', 'desc')->get();
        return View('acr_shopier::acr_orders', compact('orders'));
    }

    function admin_orders(Request $request)
    {
        $siparis                     = $request->siparis;
        $sepet_model                 = new Sepet();
        $orders                      = $sepet_model->where('siparis', 1)->with([
            'user',
            'products' => function ($query) {
                $query->with('product', 'notes');
            }
        ])->get();
        $acr_user_table_config_model = new Acr_user_table_conf();
        $config                      = $acr_user_table_config_model->first();
        $email                       = $config->email;

        $product_model = new Product();
        $products      = $product_model->whereIn('id', [
            1283,
            1284,
            1285,
            1286,
            1287,
            10
        ])->get();
        foreach ($products as $product) {
            $urun[$product->id] = $product;
        }
        return View('acr_shopier::acr_admin_orders', compact('orders', 'email', 'siparis', 'urun'));
    }

    function product_sepet_ekle(Request $request)
    {
        self::create($request);
        return redirect()->to('/acr/ftr/card/sepet')->with('msg', $this->basarili());
    }

    function create(Request $request, $product_id = null)
    {
        $sepet_model = new Sepet();
        $ps_model    = new Product_sepet();
        if (empty($product_id)) {
            $product_id = $request->input('product_id');
        }
        $notes  = $request->notes;
        $adet   = empty($request->min_adet) ? 1 : $request->min_adet;
        $min_ay = empty($request->min_ay) ? 1 : $request->min_ay;
        $adet   = empty($request->tavsiye_adet) ? 1 : $request->tavsiye_adet;
        $min_ay = empty($request->tavsiye_ay) ? 1 : $request->tavsiye_ay;
        $data   = [
            'adet'      => $adet,
            'lisans_ay' => $min_ay,
            'yaka_id'   => $request->yaka_id,
            'kol_id'    => $request->kol_id,
            'size_id'   => $request->size_id
        ];

        if (Auth::check()) {
            $sepet_id = $sepet_model->product_sepet_id();
            if (!empty($notes)) {
                foreach ($notes as $key => $note) {
                    $data_notes[] = [
                        'product_id' => $product_id,
                        'note_id'    => $request->note_ids[$key],
                        'name'       => $request->notes[$key]
                    ];
                }
            } else {
                $data_notes = [];
            }
            if ($ps_model->where('sepet_id', $sepet_id)->where('product_id', $product_id)->count() > 0) {
                return $ps_model->use_plus($product_id, $sepet_id, $data, $data_notes);
            }
        } else {
            if (empty($request->session()->get('session_id'))) {
                $session_id = rand(1000000, 99999999);
                session()->put('session_id', $session_id);
            } else {
                $session_id = session()->get('session_id');
            }
            $sepet_id = $sepet_model->product_sepet_id($session_id);
            if (!empty($notes)) {
                foreach ($notes as $key => $note) {
                    $data_notes[] = [
                        'product_id' => $product_id,
                        'note_id'    => $request->note_ids[$key],
                        'name'       => $request->notes[$key]
                    ];
                }
            } else {
                $data_notes = [];
            }
            if ($ps_model->where('product_id', $product_id)->where('sepet_id', $sepet_id)->count() > 0) {
                return $ps_model->use_plus($product_id, $sepet_id, $data, $data_notes);
            }
        }
        $session_id = empty($session_id) ? null : $session_id;
        return $sepet_model->create($session_id, $product_id, $data, $data_notes);
    }

    function delete(Request $request)
    {
        $sepet_model = new Sepet();
        $ps_model    = new Product_sepet();
        $sepet_id    = $request->input('sepet_id');
        $ps_model->where('id', $sepet_id)->delete();
        $session_id = $request->session()->get('session_id');
        return $sepet_model->sepets($session_id);
    }

    function delete_all()
    {
        $sepet_model = new Sepet();
        $sepet_model->delete_all();
    }

    function products(Request $request)
    {
        $sepet_model = new Sepet();
        $ps_model    = new Product_sepet();
        $session_id  = $request->session()->get('session_id');
        $sepet_id    = $sepet_model->product_sepet_id($session_id);
        $products    = $ps_model->where('sepet_id', $sepet_id)->with([
            'product' => function ($query) {
                $query->where('sil', 0);
            }
        ])->get();
        return self::sepet_row($products);
    }

    function price_set_lisans($product, $indirim = 0, $adet_data = null, $ay_data = null)
    {
        $priceData = empty($product->dis_price) ? $product->price : $product->dis_price;
        $price     = $priceData * $adet_data * $ay_data;
        $org_price = $price;
        $discount  = $this->dis_rate($product->price, $product->dis_price);
        $kat       = (100 - (100 * $discount)) / 100;
        $ay_kat    = $kat - $ay_data / 20;
        $adet_kat  = $kat - $adet_data / 20;
        $price     = $price * $ay_kat * $adet_kat;

        if (100 - ($price / $org_price * 100) >= $product->max_dis) {
            $org_price = $product->max_dis / 240 * $org_price;
        } else {
            $org_price = $price;
        }
        return $org_price - $indirim;

    }

    function price_set($product, $indirim = 0, $adet = null, $ay = null)
    {
        if ($product->product_id == 1282) {
            return $product->price;
        }
        $adet_data     = empty($adet) ? $product->adet : $adet;
        $ay_data       = empty($adet) ? $product->lisans_ay : $ay;
        $price_not_dis = $product->product->price * $adet_data * $ay_data;
        if ($price_not_dis == 0) {
            $price_not_dis = 1;
        }
        $priceData = empty($product->product->dis_price) ? $product->product->price : $product->product->dis_price;
        $price     = $priceData * $adet_data * $ay_data;
        if ($adet_data > 1) {
            $price     = $product->product->dis_price * $adet_data * $ay_data;
            $dis_price = $price - ($price * $product->product->dis_person * $adet_data);
        } else {
            $dis_price = $price;
        }
        if ($ay_data > 1) {
            if ($adet_data == 1) {
                $dis_price = $priceData * $adet_data * $ay_data;
            }
            $dis_price = $dis_price - ($dis_price * $product->product->dis_moon * $ay_data);
        } else {
            $dis_price = $dis_price;
        }
        if ($ay_data == 1 && $adet_data == 1) {
            $dis_price = $product->product->price;
        }
        if ((($dis_price / $price_not_dis) - ((100 - $product->product->max_dis) / 100)) < 0) {
            if (((100 - $product->product->max_dis) / 100) > 0) {
                $price = ((100 - $product->product->max_dis) / 100) * $price_not_dis;
            } else {
                $price = $dis_price;
            }
        } else {
            $price = $dis_price;
        }
        return $price - $indirim;
    }

    function discount($price = null, $dis_price = null)
    {

        $discount = 100 - round($dis_price / $price, 2) * 100;
        if ($discount > 0) {
            $discount = ' <span style="color: #0b7c0f; font-size: 9pt;">%' . $discount . '</span>';
        } else {
            $discount = '';
        }
        return $discount;
    }

    function dis_rate($price, $dis_price)
    {
        if ($price == 0) {
            return 0.1;
        };
        $discount = 100 - ($dis_price / $price * 100);
        if ($discount > 0) {
            $discount = $discount;
        } else {
            $discount = 0;
        }
        return ($discount / 100);
    }

    function sepet_row($products)
    {
        $veri           = '';
        $total_price    = [];
        $promo          = self::indirim();
        $promo_user     = $promo[0];
        $promo_user_ids = $promo[1];
        foreach ($products as $product) {
            if (empty($promo_user[$product->product_id])) {
                $indirim = 0;
            } else {
                if (in_array($product->product_id, $promo_user_ids && $promo_user[$product->product_id]['min_ay'] <= $product->lisans_ay && $promo_user[$product->product_id]['min_adet'] <= $product->adet)) {
                    $indirim = @$promo_user[$product->product_id]['price'];
                } else {
                    $indirim = 0;
                }
            }

            $price     = $product->product->price * $product->adet * $product->lisans_ay;
            $dis_price = self::price_set($product, $indirim);
            $veri      .= '<tr class="sepet_row" id="sapet_row_' . $product->id . '">
                            <td>' . $product->product->product_name . '</td>
                            <td>';
            if ($product->product_id == 1282) {
                $veri .= 1;
            } else {
                $veri .= '<input size="3" style="width: 70px; margin: 0; padding:2px;" onchange="sepet_adet_guncelle(' . $product->id . ')" onkeyup="sepet_adet_guncelle(' . $product->id . ')"
                             id="sepet_adet_' . $product->id . '" value="' . $product->adet . '"/>';
            }
            $veri .= '</td>
                             <td>';
            if ($price > $dis_price) {
                $veri          .= '<span  id="product_dis_' . $product->id . '"><strike style="color: #be3946; font-size: 9pt;">' . round($price, 2) . '</strike>   ' . self::discount($price, $dis_price) . '</span>';
                $veri          .= ' <span id="product_price_' . $product->id . '" style="color: #2d7c32; font-size: 12pt;">' . round($dis_price, 2) . '₺</span> ';
                $total_price[] = round($dis_price, 2);
            } else {
                $veri          .= ' <span id="product_price_' . $product->id . '" style="color: #2d7c32; font-size: 12pt;">' . round($price, 2) . '₺</span>';
                $total_price[] = round($price, 2);
            }
            $veri .= '</td>';
            $veri .= '<td style="text-align: right"><span style="font-size:14pt; padding-top: 6px; cursor:pointer;" onclick="sepet_delete(' . $product->id . ')" class="fa fa-trash"></span></td>
                        </tr>';
        }
        $veri .= '<tr>
                  <td></td>
                  <td></td>';
        $veri .= '<td id="acr_sepet_total_price" colspan="2">' . array_sum($total_price) . '₺</td>';
        $veri .= '</tr>';
        return $veri;
    }

    function sepet_row_detail($products)
    {
        $ps_model       = new Product_sepet();
        $spc            = new AcrSepetController();
        $ps_model       = new Product_sepet();
        $promo          = self::indirim();
        $promo_user     = $promo[0];
        $promo_user_ids = $promo[1];

        return view('acr_shopier::sepet_row', compact('ps_model', 'products', 'spc', 'promo_user', 'promo_user_ids'))->render();
    }

    function sepet_adet_guncelle(Request $request)
    {
        $sepet_model = new Sepet();
        $ps_model    = new Product_sepet();
        $sepet_id    = $request->input('sepet_id');
        $adet        = $request->input('adet');
        $ps_model->where('id', $sepet_id)->update(['adet' => $adet]);
        $session_id = $request->session()->get('session_id');
        return $sepet_model->sepets($session_id);
    }

    function sepet_lisans_ay_guncelle(Request $request)
    {
        $sepet_model = new Product_sepet();
        $sepet_id    = $request->input('sepet_id');
        $lisans_ay   = $request->input('lisans_ay');
        $sepet_model->where('id', $sepet_id)->update(['lisans_ay' => $lisans_ay]);
    }

    function card(Request $request)
    {
        $card_api   = self::card_api($request);
        $products   = $card_api->original['data']['products'];
        $order_id   = $card_api->original['data']['order_id'];
        $order_link = empty($order_id) ? '' : '?order_id=' . $order_id;
        $sepet_nav  = self::sepet_nav($order_id, 1);
        $sepet_row  = self::sepet_row_detail($products);
        $msg        = $request->session()->get('msg');
        return View('acr_shopier::card_sepet', compact('sepet_row', 'sepet_nav', 'order_link', 'msg'));
    }

    function card_api(Request $request)
    {
        $sepet_model = new Sepet();
        $product_id  = $request->input('product_id');
        $pr_model    = new Promotion();
        $pr_sayi     = $pr_model->where('product_id', $product_id)->where('type', 1)->count();
        if ($pr_sayi > 0) {
            return redirect()->back()->with('msg', $this->uyariMsj('Bu ürün yalnızca promosyon kodu ile satın alınabilir.'));
        }
        if (!empty($product_id)) {
            self::create($request, $product_id);
            return redirect()->to('/acr/ftr/card/sepet');
        }
        $session_id = session()->get('session_id');
        $products   = $sepet_model->product_sepet($session_id);
        $order_id   = $request->input('order_id');
        return response()->json([
            'status' => 1,
            'title'  => 'Bilgi',
            'msg'    => 'Sepet bilgileri çekiliyor.',
            'data'   => [
                'products' => $products,
                'order_id' => $order_id
            ]
        ]);
    }

    function adress(Request $request)
    {
        $sepet_model  = new Sepet();
        $adress_model = new AcrFtrAdress();
        $session_id   = $request->session()->get('session_id');
        if (Auth::check() && !empty($session_id)) {
            $sepet_model->sepet_birle($session_id);
            $request->session()->forget('session_id');
        }

        $order_id = $request->input('order_id');
        $order_id = empty($order_id) ? $sepet_model->product_sepet_id() : $order_id;
        $sepet    = $sepet_model->where('id', $order_id)->with([
            'products' => function ($query) {
                $query->with('product');
            }
        ])->first();
        if (!empty($sepet->products)) {
            foreach ($sepet->products as $product) {
                if ($product->adet < $product->product->min_adet) {
                    return redirect()->to('/acr/ftr/card/sepet')->with('msg', '<div style="text-align: center; margin-right: auto; margin-left: auto;" class="alert alert-danger">' . $product->product->product_name . ' ürününü en az ' . $product->product->min_adet . ' adet sipariş verebilirsiniz. </div>');
                }
            }
        }
        $sepet_nav  = self::sepet_nav($order_id, 2);
        $adres_form = self::adress_form($request);
        $userModel  = new User();
        $user       = $userModel->where('id', Auth::id())->with([
            'company' => function ($q) {
                $q->with(['city', 'county']);
            }
        ])->first();
        $adresses   = $adress_model->where('user_id', Auth::user()->id)->where('sil', 0)->with('city', 'county')->get();
        if (count($adresses) < 1) {
            $postCode  = $user->company->city_id . $user->company->county_id;
            $postCode  = $postCode == 0 || $postCode == '00' || empty($postCode) ? '34000' : $postCode;
            $adresData = [
                'user_id'      => $user->id,
                'name'         => 'İş Adresi',
                'city_id'      => $user->company->city_id,
                'county_id'    => $user->company->county_id,
                'invoice_name' => $user->name,
                'adress'       => $user->company->name . ' ' . $user->company->adress,
                'tc'           => empty($user->TC) ? '11111111111' : $user->TC,
                'company'      => $user->company->name,
                'post_code'    => $postCode,
                'tel'          => $user->tel,
            ];
            $adress_model->insert($adresData);
            return redirect()->to('/acr/ftr/card/adress');
        }
        $order_link  = empty($order_id) ? '' : '?order_id=' . $order_id;
        $order_input = empty($order_id) ? '' : '<input name="order_id" type="hidden" value="' . $order_id . '"/>';
        self::ps_dis_rate_set($order_id); // product_sepet dis_rate oranlarını hesaplar
        return View('acr_shopier::card_adress', compact('sepet_nav', 'adres_form', 'adresses', 'order_input', 'order_link'));
    }

    function ps_dis_rate_set($order_id)
    {
        $ps_model = new Product_sepet();
        $pss      = $ps_model->where('sepet_id', $order_id)->get();
        foreach ($pss as $ps) {
            $price         = self::price_set($ps);
            $dis_not_price = self::product_not_dis_price($ps);
            $dis_rate      = self::dis_rate($dis_not_price, $price);
            $ps_model->where('id', $ps->id)->update(['dis_rate' => $dis_rate]);
        }
    }

    function apiSecret()
    {
        $iyzi_model = new AcrFtrIyzico();
        $iyzico     = $iyzi_model->first();
        return $iyzico->setSecretKey;
    }

    function shopierClass()
    {
        $iyzi_model = new AcrFtrIyzico();
        $iyzico     = $iyzi_model->first();
        $apiSecret  = $this->apiSecret();
        return new Shopier($iyzico->setApiKey, $apiSecret);
    }

    function shopierRenderButton($order)
    {
        $userModel  = new User();
        $adresModel = new AcrFtrAdress();
        $sepetModel = new Sepet();
        $request    = new Request();
        $adress     = $adresModel->where('user_id', Auth::id())->where('active', 1)->with(['city', 'county'])->first();
        $user       = $userModel->where('id', Auth::id())->first();
        $shopier    = $this->shopierClass();
        // Satın alan kişi bilgileri
        $buyer = new Buyer([
            'id'          => $user->id,
            'name'        => $user->name,
            'surname'     => $user->name,
            'email'       => $user->email,
            'phone'       => $user->tel,
            'account_age' => date('Y') - date('Y', strtotime($user->created_at)),


        ]);
        // Fatura ve kargo adresi birlikte tanımlama
        // Ayrı ayrı da tanımlabilir
        $address = new Address([
            'address'  => $adress->adress,
            'city'     => $adress->city->name,
            'country'  => 'Turkey',
            'postcode' => @$adress->post_code,
            'cargo'    => 1
        ]);

        // shopier parametlerini al
        $params = $shopier->getParams();

        // Satın alan kişi bilgisini ekle
        $params->setBuyer($buyer);

        // Fatura ve kargo adresini aynı şekilde ekle
        $params->setAddress($address);
        $name = 'Site Aboneliği';


        foreach ($order->products as $key => $product) {
            if ($product->id != 1282) {
                $prices[] = round(self::price_set($product), 2);
            } else {
                $prices[] = $product->price;
            }
            $name .= $product->product->name;
            if (count($order->products) - 1 > $key) {
                $name .= ',';
            }
        }
        //$fiyat_data = $this->lisans_urun_fiyat_hesapla($order->adet, $order->lisans_ay,$order->product_id );
        $price = round(array_sum($prices), 2);
        $sepetModel->where('user_id', $user->id)->where('id', $order->id)->update(['price' => $price]);


        // Sipariş numarsı ve sipariş tutarını ekle
        $shopier->setOrderData($order->id, $price);
        // Sipariş edilen ürünü ekle

        $shopier->setProductData($name, 'dijital');
        try {
            /**
             * Otomarik ödeme sayfasına yönlendiren renderer
             *
             * @var AutoSubmitFormRenderer $renderer
             */
            $renderer = $shopier->createRenderer(AutoSubmitFormRenderer::class);
            /**
             * Shopier İle Güvenli Öde şeklinde butona tıklanınca
             * ödeme sayfasına yönlendirenn renderer
             *
             * @var ButtonRenderer $renderer
             */
            $renderer = $shopier->createRenderer(ButtonRenderer::class);
            return $renderer->withStyle("padding:15px; color: #fff; background-color:#51cbb0; border:1px solid #fff; border-radius:7px")->withText('Kredi/Banka Kartı ile Satın Al');
        } catch (RequiredParameterException $e) {
            // Zorunlu parametlerden bir ve daha fazlası eksik

            dd('Zorunlu parametlerden bir ve daha fazlası eksik' . $e);
        } catch (NotRendererClassException $e) {
            // $shopier->createRenderer(...) metodunda verilen class adı AbstracRenderer sınıfından türetilmemiş !
            dd('metodunda verilen class adı AbstracRenderer sınıfından türetilmemiş !');
        } catch (RendererClassNotFoundException $e) {
            // $shopier->createRenderer(...) metodunda verilen class bulunamadı !
            dd('metodunda verilen class bulunamadı !');
        }
        //return view('market.marketTest');
    }

    function order_result(Request $req)
    {
        $sepet_model = new Sepet();
        # make request
        $shopier                = $this->shopierClass();
        $response_data          = [
            'platform_order_id' => $req->platform_order_id,
            'status'            => $req->status,
            'installment'       => $req->installment,
            'payment_id'        => $req->payment_id,
            'random_nr'         => $req->random_nr,
            'signature'         => $req->signature

        ];
        $isValidPaymentResponse = $shopier->verifyResponse($response_data);
        if ($isValidPaymentResponse) {
            if ($req->status == 'success') {
                $sepet_id = $req->platform_order_id;
                $siparis  = $sepet_model->where('id', $sepet_id)->first();
                if ($siparis->siparis_onay != 1) {
                    $sepet_model->where('id', $sepet_id)->update(['order_result' => 2, 'siparis' => 1]);
                    $sepetController = new AcrSepetController();
                    return $sepetController->orders_active($req, $sepet_id);
                }
            }
        }
    }

    function payment(Request $request)
    {
        $shopier     = $this->shopierClass();
        $sepet_model = new Sepet();
        $bank_model  = new Bank();
        $order_id    = $request->input('order_id');
        $order_id    = empty($order_id) ? $sepet_model->product_sepet_id() : $order_id;
        $order_link  = empty($order_id) ? '' : '?order_id=' . $order_id;
        $adress_id   = $request->input('adress');
        self::adres_secimi_api($request, $adress_id);
        $sepet_model->where('id', $order_id)->update(['adress_id' => $adress_id]);
        $order_input    = empty($order_id) ? '' : '<input name="order_id" type="hidden" value="' . $order_id . '"/>';
        $banks          = $bank_model->where('active', 1)->where('sil', 0)->get();
        $sepet_nav      = self::sepet_nav($order_id, 3);
        $order          = $sepet_model->where('id', $order_id)->with([
            'products' => function ($q) {
                $q->with([
                    'product'
                ]);
            },
        ])->first();
        $bankCardButton = $this->shopierRenderButton($order);


        return View('acr_shopier::card_payment', compact('sepet_nav', 'banks', 'order_link', 'bankCardButton', 'order_input', 'shopier'));
    }

    function adres_secimi_api(Request $request, $adress_id = null)
    {
        $adress_id    = empty($adress_id) ? $request->adress_id : $adress_id;
        $adress_model = new AcrFtrAdress();
        if (!empty($adress_id)) {
            $adress_model->active_adress($adress_id);
        }
        return response()->json([
            'status' => 1,
            'title'  => 'Bilgi',
            'msg'    => 'Adres aktif edildi.',
            'data'   => null
        ]);

    }

    function adresses_api()
    {
        $adress_model = new AcrFtrAdress();
        $adresses     = $adress_model->where('user_id', Auth::user()->id)->where('sil', 0)->with('city', 'county')->get();
        return response()->json([
            'status' => 1,
            'title'  => 'Bilgi',
            'msg'    => 'Adres bilgileri çekiliyor.',
            'data'   => $adresses
        ]);

    }

    function sepet_total_price($sepet_id = null, Request $request)
    {
        $sepet_id = empty($sepet_id) ? $request->sepet_id : $sepet_id;
        $ps_model = new Product_sepet();
        $product  = $ps_model->where('id', $sepet_id)->with('product')->first();
        $price    = self::price_set($product);
        if ($product->id != 1282) {
            return round($price, 2);
        }
        $not_dis_price = $product->product->price * $product->adet * $product->lisans_ay;
        $dis_rate      = self::dis_rate($not_dis_price, $price);
        if ($product->dis_rate != $dis_rate) {
            $ps_model->where('id', $sepet_id)->update(['dis_rate' => $dis_rate]);
        }
        $prices = round($price, 2);
        return $prices;
    }

    function product_not_dis_price($product)
    {
        $price = $product->product->price * $product->adet * $product->lisans_ay;
        return $price;
    }

    function not_dis_price($sepet_id = null, Request $request)
    {
        $ps_model    = new Product_sepet();
        $sepet_id    = empty($sepet_id) ? $request->sepet_id : $sepet_id;
        $productData = $ps_model->where('id', $sepet_id)->first();
        $products    = $ps_model->where('sepet_id', $productData->sepet_id)->with('product')->get();
        $price       = [];
        foreach ($products as $product) {
            $price[] = $product->product->price * $product->adet * $product->lisans_ay;
        }
        $prices = array_sum($price);
        $prices = round($prices, 2);
        return $prices;
    }

    function product_sepet_total_price($sepet_id = null, Request $request, $indirim = 0)
    {
        $ps_model    = new Product_sepet();
        $sepet_id    = empty($sepet_id) ? $request->sepet_id : $sepet_id;
        $productData = $ps_model->where('id', $sepet_id)->first();
        $products    = $ps_model->where('sepet_id', $productData->sepet_id)->with('product')->get();
        $price       = [];
        foreach ($products as $product) {
            if ($product->product_id != 1282) {
                $price[] = self::price_set($product, $indirim);
            } else {
                $price[] = $product->price;
            }
        }
        $prices = array_sum($price);
        $prices = round($prices, 2);
        return $prices;
    }

    function order_set($data, $sepet_id)
    {
        $sepet_model = new Sepet();
        $sepet_model->where('id', $sepet_id)->update($data);
    }

    function paymet_havale_eft(Request $request)
    {
        $veri            = self::payment_havale_eft_api($request);
        $sepetController = new AcrSepetController();
        $sepet_nav       = $veri->original['data']['sepet_nav'];
        $siparis         = $veri->original['data']['siparis'];
        $bank            = $veri->original['data']['bank'];
        $ps              = $veri->original['data']['ps'];
        $user_adress     = $veri->original['data']['user_adress'];
        $company         = $veri->original['data']['company'];
        $promo           = self::indirim();
        $promo_user      = $promo[0];
        $promo_user_ids  = $promo[1];
        return View('acr_shopier::card_result_bank', compact('sepet_nav', 'siparis', 'bank', 'ps', 'user_adress', 'company', 'sepetController', 'promo_user', 'promo_user_ids'));
    }

    function payment_havale_eft_api(Request $request)
    {
        $promo          = self::indirim();
        $promo_user     = $promo[0];
        $promo_user_ids = $promo[1];
        $sepet_model    = new Sepet();
        $bank_id        = $request->input('bank_id');
        $bank_model     = new Bank();
        $order_id       = $request->input('order_id');
        $sepet_id       = empty($order_id) ? $sepet_model->product_sepet_id() : $order_id;
        $ps_model       = new Product_sepet();
        $ps             = $ps_model->where('sepet_id', $sepet_id)->first();

        if (in_array($ps->product_id, $promo_user_ids) && $promo_user[$ps->product_id]['min_ay'] <= $ps->lisans_ay && $promo_user[$ps->product_id]['min_adet'] <= $ps->adet) {
            $indirim = $promo_user[$ps->product_id]['price'];
        } else {
            $indirim = 0;
        }

        $price         = round(self::product_sepet_total_price($ps->id, $request), 2) - $indirim;
        $not_dis_price = round(self::not_dis_price($ps->id, $request), 2) - $indirim;
        $dis_rate      = self::dis_rate($not_dis_price, $price);
        $data_sepet    = [
            'siparis'      => 1,
            'price'        => $price,
            'bank_id'      => $bank_id,
            'payment_type' => 1,
            'dis_rate'     => $dis_rate
        ];
        self::order_set($data_sepet, $sepet_id);
        $siparis = $sepet_model->where('id', $sepet_id)->where('siparis', 1)->first();
        $ps      = $ps_model->where('sepet_id', $sepet_id)->with('product')->get();
        if (empty($sepet_id)) {
            return redirect()->to('/acr/ftr/orders');
        }
        $bank          = $bank_model->where('id', $bank_id)->first();
        $sepet_nav     = self::sepet_nav($sepet_id, 4);
        $adress_model  = new AcrFtrAdress();
        $user_adress   = $adress_model->where('id', $siparis->adress_id)->with('city', 'county')->first();
        $company_model = new Company_conf();
        $company       = $company_model->first();
        return response()->json([
            'status' => 1,
            'title'  => 'Bilgi',
            'msg'    => 'Siparişiniz  oluşturuldu ödeme bekleniyor.',
            'data'   => [
                'ps'          => $ps,
                'bank'        => $bank,
                'sepet_nav'   => $sepet_nav,
                'user_adress' => $user_adress,
                'company'     => $company,
                'siparis'     => $siparis
            ]
        ]);
    }

    function payment_bank_card(Request $request)
    {
        $veri             = self::payment_bank_card_api($request);
        $sepetController  = new AcrSepetController();
        $sepet_nav        = $veri->original['data']['sepet_nav'];
        $siparis          = $veri->original['data']['siparis'];
        $ps               = $veri->original['data']['ps'];
        $user_adress      = $veri->original['data']['user_adress'];
        $company          = $veri->original['data']['company'];
        $iyzicoController = new iyzicoController();
        $promo            = self::indirim();
        $promo_user       = $promo[0];
        $promo_user_ids   = $promo[1];
        $odemeForm        = $iyzicoController->odemeForm(1, $siparis->price, $siparis->id);
        return View('acr_shopier::card_result_bank_card', compact('sepet_nav', 'siparis', 'odemeForm', 'ps', 'user_adress', 'company', 'sepetController', 'promo_user', 'promo_user_ids'));

    }

    function payment_bank_card_api(Request $request)
    {
        $promo          = self::indirim();
        $promo_user     = $promo[0];
        $promo_user_ids = $promo[1];
        $sepet_model    = new Sepet();
        $order_id       = $request->input('order_id');
        $sepet_id       = empty($order_id) ? $sepet_model->product_sepet_id() : $order_id;
        $ps_model       = new Product_sepet();
        $ps             = $ps_model->where('sepet_id', $sepet_id)->first();

        if (in_array($ps->product_id, $promo_user_ids) && $promo_user[$ps->product_id]['min_ay'] <= $ps->lisans_ay && $promo_user[$ps->product_id]['min_adet'] <= $ps->adet) {
            $indirim = $promo_user[$ps->product_id]['price'];
        } else {
            $indirim = 0;
        }

        $price         = round(self::product_sepet_total_price($ps->id, $request), 2) - $indirim;
        $not_dis_price = round(self::not_dis_price($ps->id, $request), 2) - $indirim;
        $dis_rate      = self::dis_rate($not_dis_price, $price);
        $data_sepet    = [
            'siparis'      => 1,
            'price'        => $price,
            'payment_type' => 2,
            'dis_rate'     => $dis_rate
        ];
        self::order_set($data_sepet, $sepet_id);
        $siparis = $sepet_model->where('id', $sepet_id)->where('siparis', 1)->first();
        $ps      = $ps_model->where('sepet_id', $sepet_id)->with('product')->get();
        if (empty($sepet_id)) {
            return redirect()->to('/acr/ftr/orders');
        }
        $sepet_nav     = self::sepet_nav($sepet_id, 4);
        $adress_model  = new AcrFtrAdress();
        $user_adress   = $adress_model->where('id', $siparis->adress_id)->with('city', 'county')->first();
        $company_model = new Company_conf();
        $company       = $company_model->first();
        return response()->json([
            'status' => 1,
            'title'  => 'Bilgi',
            'msg'    => 'Kredi kartı için ödeme bekleniyor.',
            'data'   => [
                'ps'          => $ps,
                'sepet_nav'   => $sepet_nav,
                'user_adress' => $user_adress,
                'company'     => $company,
                'siparis'     => $siparis
            ]
        ]);
    }

    function sepet_nav($order_id = null, $step)
    {
        $sepet_link = empty($order_id) ? '' : '?order_id=' . $order_id;
        $navs       = [
            1 => [
                'sepet',
                'SEPET',
                11
            ],
            [
                'adress',
                'ADRES/FATURA',
                10
            ],
            [
                'payment',
                'ÖDEME YÖNETİ',
                9
            ],
            [
                'result',
                ' ÖDEME',
                8
            ]
        ];

        $row = '<div id="breadcrumb">';
        $row .= '<ul class="crumbs">';
        foreach ($navs as $key => $nav) {
            $color = $key == $step ? 'color: red; background-position: 100% -96px;' : '';
            $row   .= '<li class="first ">';
            $row   .= '<a ';
            if ($step >= $key) {
                $row .= ' href="/acr/ftr/card/' . $nav[0] . $sepet_link . '"';
            }
            $row .= 'style="z-index:' . $nav[2] . '; ' . $color . '"><span></span>' . $key . ' ' . $nav[1] . '</a>';
            $row .= '</li>';
        }
        $row .= '</ul>';
        $row .= '</div>';
        return $row;
    }

    function adress_form(Request $request, $adress = null, $user_id = null)
    {
        $city_model = new City();
        $cities     = $city_model->get();
        $county_row = $this->county_row($request, $adress);
        $row        = view('acr_shopier::adress_form', compact('cities', 'county_row', 'adress'))->render();
        return $row;
    }

    function county_row(Request $request, $adress = null)
    {
        $county_model = new County();
        $city_id      = @$adress->city_id;
        if (empty($city_id)) {
            $city_id = $request->input('city_id');
        }
        $counties = $county_model->where('city_id', $city_id)->get();
        // citys
        $row = view('acr_shopier::county_row', compact('counties', 'adress'))->render();

        return $row;
    }

    function adress_create(Request $request)
    {
        self::adress_create_api($request);
        return Redirect()->back();
    }

    function adress_create_api(Request $request)
    {
        $roles = AcrMenu::roles();
        if (in_array(1, $roles)) {
            $user_id = empty($request->user_id) ? Auth::user()->id : $request->user_id;

        } else {
            $user_id = Auth::user()->id;
        }
        $rules   = array(
            'name'      => 'required',
            // make sure the email is an actual email
            'city'      => 'required',
            // password can only be alphanumeric and has to be greater than 3 characters
            'county'    => 'required',
            'post_code' => 'required',
            'tel'       => 'required'
        );
        $massage = [
            'name.required'      => 'Adres İsmi Giriniz',
            'city.required'      => 'Şehir Seçiniz.',
            'county.required'    => 'İlçe Seçiniz.',
            'post_code.required' => 'Posta Kodu Giriniz.',
            'tel.required'       => 'Telefon Numarası Giriniz.'
        ];
        // run the validation rules on the inputs from the form
        $validator = Validator::make($request->all(), $rules, $massage);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect()->back()->withErrors($validator)// send back all errors to the login form
            ->withInput($request->all()); //
        } else {
            $e_fatura         = empty($request->input('e_fatura')) ? 1 : $request->input('e_fatura');
            $adress_model     = new AcrFtrAdress();
            $invoice_name_exp = explode(' ', $request->input('invoice_name'));
            if (empty($invoice_name_exp)) {
                $invoice_name = $request->invoice_name . ' invoice_name';
            } else {
                $invoice_name = $request->invoice_name;
            }
            $tc        = strlen($request->input('tc')) < 11 ? '11111111111' : $request->input('tc');
            $data      = [
                'user_id'      => $user_id,
                'name'         => $request->input('name'),
                'invoice_name' => $invoice_name,
                'tc'           => $tc,
                'adress'       => $request->input('adress'),
                'city_id'      => $request->input('city'),
                'county_id'    => $request->input('county'),
                'post_code'    => $request->input('post_code'),
                'tel'          => $request->input('tel'),
                'type'         => $request->input('type'),
                'company'      => $request->input('company'),
                'tax_number'   => $request->input('tax_number'),
                'tax_office'   => $request->input('tax_office'),
                'e_fatura'     => $e_fatura,
                'active'       => 1,

            ];
            $adress_id = $request->input('adress_id') ? $request->input('adress_id') : 0;
            $adress_id = $adress_model->create($adress_id, $data);
            self::parasut_contact_update($adress_id);
            return response()->json([
                'status' => 1,
                'title'  => 'Bilgi',
                'msg'    => 'Adres bilgileri başarıyla eklendi.',
                'data'   => null
            ]);

        }
    }

    function parasut_contact_update($adress_id)
    {
        $adress_model    = new AcrFtrAdress();
        $parasut         = new ParasutController();
        $adress_row      = $adress_model->where('id', $adress_id)->first();
        $parasut_contact = self::parasut_contact_data($adress_row);
        if (empty($adress_row->parasut_id)) {
            $contact_id = $parasut->contact($parasut_contact);
            $adress_model->where('id', $adress_id)->update(['parasut_id' => $contact_id]);
        } else {
            $parasut->contact_update($adress_row->parasut_id, $parasut_contact);
        }
    }

    function card_adress_edit(Request $request)
    {
        $adress_model = new AcrFtrAdress();
        $sepet_id     = $request->input('sepet_id');
        $sepet_nav    = self::sepet_nav($sepet_id, 2);
        $adres_id     = $request->input('adres_id');
        $user_id      = $request->user_id;
        $adress       = $adress_model->where('id', $adres_id)->with('city', 'county')->first();
        $adres_form   = self::adress_form($request, $adress, $user_id);


        return View('acr_shopier::card_adress_edit', compact('sepet_nav', 'adres_form', 'adress'));
    }

    function adress_edit(Request $request)
    {
        $adress_model = new AcrFtrAdress();
        $adres_id     = $request->input('adres_id');
        $user_id      = $request->user_id;
        $adress       = $adress_model->where('id', $adres_id)->first();
        return self::adress_form($request, $adress, $user_id);
    }

    function adress_delete(Request $request)
    {
        $adress_model = new AcrFtrAdress();
        $adres_id     = $request->input('adres_id');
        $roles        = AcrMenu::roles();
        if (in_array(1, $roles)) {
            $adress_model->where('id', $adres_id)->update(['sil' => 1]);

        } else {
            $adress_model->where('id', $adres_id)->where('user_id', Auth::user()->id)->update(['sil' => 1]);
        }
    }

    function parasut_contact_data($adress_row)
    {

        if ($adress_row->type == 1) {
            $contact_type = 'person';
            $tax_number   = $adress_row->tc;
            $invoice_name = $adress_row->invoice_name;
            $contact_name = $adress_row->invoice_name;
        } else {
            $contact_type = 'company';
            $tax_number   = $adress_row->tax_number;
            $invoice_name = $adress_row->company;
            $contact_name = $adress_row->invoice_name;
        }
        $user_email      = $this->config_email;
        $parasut_contact = [
            'name'                      => $invoice_name,
            'contact_type'              => $contact_type,
            'tax_number'                => $tax_number,
            'tax_office'                => $adress_row->office,
            'category_id'               => null,
            'city'                      => @$adress_row->city->name,
            'district'                  => @$adress_row->county->name,
            'email'                     => Auth::user()->$user_email,
            'address_attributes'        => [
                'address' => $adress_row->adress,
                'phone'   => $adress_row->tel,
                'fax'     => null,
            ],
            'contact_people_attributes' => [
                [
                    'name'  => $contact_name,
                    'phone' => $adress_row->tel,
                ],
            ],
        ];
        return $parasut_contact;
    }

    function admin_e_arsive_create(Request $request)
    {
        $fatura_model   = new Fatura();
        $sepet_model    = new Sepet();
        $fatura_id      = $request->fatura_id;
        $fatura         = $fatura_model->where('id', $fatura_id)->first();
        $user_mail_conf = $this->config_email;
        $user_email     = $fatura->user->$user_mail_conf;
        $fatura_model->where('id', $fatura_id)->update(['tarih' => date('Y-m-d')]);
        $sepet_model->where('id', $fatura_id)->update(['updated_at' => date('Y-m-d')]);
        return self::orders_active($request, $fatura->order_id, 1, 1);
    }

    function orders_active_admin(Request $request, $order_id = null)
    {
        $order_id    = empty($order_id) ? $request->input('order_id') : $order_id;
        $sepet_model = new Sepet();
        $sepet_model->where('id', $order_id)->update(['order_result' => 2]);
        self::orders_active($request, $order_id, 1);
    }

    function fatura_olustur($data, $data_product)
    {
        $fatura_model = new Fatura();
        $fatura_model->insert($data);
        $fatura_product_model = new Fatura_product();
        $fatura_product_model->insert($data_product);
    }

    function orders_active(Request $request, $order_id = null, $admin = null, $e_arsive_create = null)
    {
        $parasut           = new ParasutController();
        $order_id          = empty($order_id) ? $request->input('order_id') : $order_id;
        $sepet_model       = new Sepet();
        $ps_model          = new Product_sepet();
        $market_controller = new MarketController();
        $company_model     = new Company_conf();
        $company           = $company_model->first();
        /*$parasut_conf     = new Parasut_conf();
        $parasut_conf_row = $parasut_conf->where('user_id', Auth::user()->id)->first();*/
        $adress_model = new AcrFtrAdress();
        $sepet_row    = $sepet_model->where('id', $order_id)->with([
            'products' => function ($query) use ($order_id) {
                $query->with([
                    'product' => function ($query) {
                        $query->with([
                            'user_product' => function ($query) {
                                $query->with(['user']);
                            }
                        ]);
                    },
                    'size',
                    'kol',
                    'yaka',
                    'notes'   => function ($query) use ($order_id) {
                        $query->where('sepet_id', $order_id);
                    }
                ]);
            },
            'adress'   => function ($query) {
                $query->where('active', 1);
                $query->with([
                    'city',
                    'county'
                ]);
            }
        ])->first();
        if ($sepet_row->order_result == 2 && $sepet_row->active == 0 || $e_arsive_create == 1) {
            $adress_row = $adress_model->where('active', 1)->where('user_id', $sepet_row->user_id)->with('city', 'county')->first();
            if (empty($adress_row->parasut_id)) {
                $adress             = $adress_model->find($adress_row->id);
                $parasut_contact    = self::parasut_contact_data($adress_row);
                $parasut_contact_id = $parasut->contact($parasut_contact);
                $adress->parasut_id = $parasut_contact_id;
                $adress->save();
            } else {
                $parasut_contact_id = $adress_row->parasut_id;
            }
            $orders         = $ps_model->where('sepet_id', $order_id)->with([
                'product',
                'acr_product',
                'sepet'
            ])->get();
            $promo          = self::indirim();
            $promo_user     = $promo[0];
            $promo_user_ids = $promo[1];
            foreach ($orders as $order) {
                if (is_array($promo_user_ids)) {
                    if (in_array($order->product_id, $promo_user_ids) && $promo_user[$order->product_id]['min_ay'] <= $order->lisans_ay && $promo_user[$order->product_id]['min_adet'] <= $order->adet) {
                        $indirim = $promo_user[$order->product_id]['price'];
                    } else {
                        $indirim = 0;
                    }
                } else {
                    $indirim = 0;
                }
                if ($order->product->fatura_bas == 1 && $sepet_row->fatura_active == 1) {
                    $urun_names [] = $order->product->product_name;
                    if (empty($order->product->collection) || $order->product->collection == '0.00') {
                        $kdv                    = $order->product->kdv;
                        $ps_price               = self::sepet_total_price($order->id, $request, $indirim);
                        $fiyat                  = round(((($ps_price * ((100) / (100 + $kdv)))) / $order->adet), 4);
                        $parasut_product_data[] = [
                            'product_id'    => $order->acr_product->parasut_id,
                            // the parasut products
                            'quantity'      => $order->adet,
                            'unit_price'    => $fiyat,
                            'discount'      => round($order->product->price * $order->sepet->dis_rate, 4),
                            'vat_rate'      => $kdv,
                            'discount_type' => 'amount',
                            'discount_rate' => $order->sepet->dis_rate,
                        ];
                        $total_vat[]            = ($order->product->price - ($order->product->price * $order->sepet->dis_rate)) * $kdv;

                    } else {
                        $kdv                    = $order->product->collection_kdv;
                        $fiyat                  = round(((($order->product->collection * ((100) / (100 + $kdv)))) / $order->adet), 4);
                        $parasut_product_data[] = [
                            'product_id'    => $order->acr_product->parasut_id,
                            // the parasut products
                            'quantity'      => $order->adet,
                            'unit_price'    => $fiyat,
                            'discount'      => 0,
                            'vat_rate'      => $kdv,
                            'discount_type' => 'amount',
                            'discount_rate' => 0,
                        ];
                        $total_vat[]            = ($order->product->price - ($order->product->price * $order->sepet->dis_rate)) * $kdv;
                    }
                    $acr_fatura_product[] = [
                        'order_id'     => $order_id,
                        'name'         => $order->product->product_name,
                        'kdv'          => $order->adet * (0.18 * $fiyat),
                        'fiyat'        => $order->adet * $fiyat,
                        'toplam_fiyat' => $order->adet * ($fiyat + (0.18 * $fiyat)),
                        'adet'         => $order->adet,
                    ];
                    $fatura_bas           = 1;
                } else {
                    $fatura_bas = 2;
                }
            }
            if ($fatura_bas == 1) {
                $payment_add_contact = ['balance' => $sepet_row->price];
                $parasut->contact_update($parasut_contact_id, $payment_add_contact);
                $parasut_sale_data = [
                    'description'        => $adress_row->invoice_name,
                    'item_type'          => 'invoice',
                    'contact_id'         => $parasut_contact_id,
                    'gross_total'        => round(($sepet_row->price * (100 / 118)), 2),
                    'net_total'          => $sepet_row->price,
                    'archived'           => null,
                    'issue_date'         => date('Y-m-d'),
                    'details_attributes' => $parasut_product_data,
                    'total_paid'         => $sepet_row->price,
                    'payment_status'     => 'paid',
                    'payments'           => [
                        "id"           => 1,
                        "payable_id"   => 1,
                        "payable_type" => "SalesInvoice",
                        "amount"       => $sepet_row->price,
                        "notes"        => null,
                        "flow"         => "in",
                        "is_overdue"   => false,
                        "is_paid"      => true,
                    ]
                ];
                $invoice           = $parasut->sale($parasut_sale_data);

                $fatura_data = [
                    'order_id'           => $order_id,
                    'parasut_invoice_id' => $invoice->id,
                    'invoice_name'       => $adress_row->invoice_name,
                    'adress'             => $adress_row->adress . ' ' . $adress_row->county->name . '/' . $adress_row->city->name,
                    'tax_office'         => $adress_row->tax_office,
                    'tax_number'         => $adress_row->tax_number,
                    'tc'                 => $adress_row->tc,
                    'tarih'              => $adress_row->updated_at,
                    'user_id'            => $adress_row->user_id,
                    'tel'                => $adress_row->tel,
                    'post_code'          => $adress_row->post_code,
                    'type'               => $adress_row->type,
                    'payment_type'       => $sepet_row->payment_type,
                    'guncel'             => 1,
                    // '0 eski 1 güncel,
                    'fiyat'              => $sepet_row->price,
                    'fiyat_yazi'         => self::paraYazi($sepet_row->price)
                ];
                if ($e_arsive_create != 1) {
                    self::fatura_olustur($fatura_data, $acr_fatura_product); // sistem içinde tutulan faturalar
                }
                //  dd($invoice_id);
                /*$payment_data = [
                    "amount"        => $sepet_row->price,
                    "date"          => date('Y-m-d'),
                    // "description"   => "Açıklama",
                    "account_id"    => $parasut->account_id,
                    "exchange_rate" => "1.0"
                ];
                $parasut->paid($invoice->id, $payment_data);*/
                $email_user_conf = $this->config_email;

                $user_email = $sepet_row->user->$email_user_conf;
                self::e_arsiv_create($sepet_row->payment_type, $user_email, $invoice->id);
            }
        }
        if (@$fatura_bas == 1) {
            $mesaj = 'Ödeme Bilgileri<br>';
            $mesaj .= $adress_row->invoice_name . '<br>';
            $mesaj .= $adress_row->tel . '<br>';
            $mesaj .= 'Ürünler : ';
            foreach ($urun_names as $urun_name) {
                $mesaj .= $urun_name . ',';
            }
            $mesaj .= '<br>';
            $mesaj .= $sepet_row->price . '₺';
            $my    = new my();
            if ($admin != 1) {
                $my->mail($company->email, 'Okul Öncesi Evrak', 'Ödeme', 'mail.odeme', $mesaj);
            }
            if ($e_arsive_create == 1) {
                return redirect()->to('/admin/e_arsive/basarili');
            }
        }
        // gülay duman mail gönderimi g.duman4043@gmail.com
        foreach ($sepet_row->products as $product) {
            if (!empty($product->product->user_product->user->email)) {
                $view = '';
                $view .= '<table class="table table-bordered">';
                $view .= '<tr>';
                $view .= '<td>';
                $view .= 'Ürün';
                $view .= '</td>';
                $view .= '<td>';
                $view .= $product->product->product_name;
                $view .= '</td>';
                $view .= '<td>';
                $view .= 'Adres Tanımı';
                $view .= '</td>';
                $view .= '<td>';
                $view .= $sepet_row->adress->incoice_name;
                $view .= '</td>';
                $view .= '</tr>';

                $view .= '<tr>';
                $view .= '<td>';
                $view .= 'Telefon 1 ';
                $view .= '</td>';
                $view .= '<td>';
                $view .= $sepet_row->adress->tel;
                $view .= '</td>';
                $view .= '<td>';
                $view .= 'Telefon 2';
                $view .= '</td>';
                $view .= '<td>';
                $view .= $sepet_row->user->tel;
                $view .= '</td>';
                $view .= '</tr>';


                $view .= '<tr>';
                $view .= '<td colspan="2">';
                $view .= 'Ürün Detayları';
                $view .= '</td>';
                $view .= '</tr>';


                $view .= '<tr>';
                $view .= '<td>';
                $view .= 'Beden ';
                $view .= '</td>';
                $view .= '<td>';
                $view .= @$product->size->name;
                $view .= '</td>';
                $view .= '<td>';
                $view .= 'Kol';
                $view .= '</td>';
                $view .= '<td>';
                $view .= @$product->kol->name;
                $view .= '</td>';
                $view .= '</tr>';

                $view .= '<tr>';
                $view .= '<td>';
                $view .= 'Yaka ';
                $view .= '</td>';
                $view .= '<td>';
                $view .= @$product->yaka->name;
                $view .= '</td>';
                $view .= '<td>';
                $view .= 'Notlar';
                $view .= '</td>';
                $view .= '<td>';
                if (!empty($product->notes)) {
                    foreach ($product->notes as $note) {
                        $view .= $note->note->name . ':' . $note->name . '<br>';
                    }
                }
                $view .= '</td>';
                $view .= '</tr>';

                $view .= '<tr>';
                $view .= '<td>';
                $view .= 'Alacak Kişi';
                $view .= '</td>';
                $view .= '<td>';
                $view .= $sepet_row->adress->name;
                $view .= '</td>';
                $view .= '<td>';
                $view .= 'Adres';
                $view .= '</td>';
                $view .= '<td>';
                $view .= $sepet_row->adress->adress;
                $view .= '</td>';
                $view .= '</tr>';


                $view .= '<tr>';
                $view .= '<td>';
                $view .= 'T.C.';
                $view .= '</td>';
                $view .= '<td>';
                $view .= $sepet_row->adress->tc;
                $view .= '</td>';
                $view .= '<td>';
                $view .= 'Şirket';
                $view .= '</td>';
                $view .= '<td>';
                $view .= $sepet_row->adress->company;
                $view .= '</td>';
                $view .= '</tr>';

                $view .= '<tr>';
                $view .= '<td>';
                $view .= 'Vergi Dairesi';
                $view .= '</td>';
                $view .= '<td>';
                $view .= $sepet_row->adress->tax_number;
                $view .= '</td>';
                $view .= '<td>';
                $view .= 'Vergi Numarası';
                $view .= '</td>';
                $view .= '<td>';
                $view .= $sepet_row->adress->tax_office;
                $view .= '</td>';
                $view .= '</tr>';

                $view .= '<tr>';
                $view .= '<td>';
                $view .= 'Şehir';
                $view .= '</td>';
                $view .= '<td>';
                $view .= @$sepet_row->adress->city->name;
                $view .= '</td>';
                $view .= '<td>';
                $view .= 'İlçe';
                $view .= '</td>';
                $view .= '<td>';
                $view .= @$sepet_row->adress->county->name;
                $view .= '</td>';
                $view .= '</tr>';


                $view .= '</table>';

                $this->ftr_mail($product->product->user_product->user->email, @$product->product->user_product->user->name, "Yeni Sipariş", "acr_shopier::mail.odeme", @$view);
            }
        }
        return $market_controller->order_result(null, $order_id);
    }

    function e_arsiv_create($payment_type, $user_email, $invoice_id)
    {
        $parasut       = new ParasutController();
        $company_model = new Company_conf();
        $company       = $company_model->first();
        $payment       = $payment_type == 1 ? 'KREDIKARTI/BANKAKARTI' : 'EFT/HAVALE';
        $e_arsiv       = [
            "note"          => "Bu fatura $company->url aracılığıyla oluşturulmuştur.",
            "to"            => "urn=>mail=>$user_email",
            'internet_sale' => [
                'url'              => $company->url,
                'payment_type'     => $payment,
                'payment_platform' => '-',
                'payment_date'     => date('Y-m-d')
            ]
        ];
        $parasut->e_arsiv($invoice_id, $e_arsiv);
    }

    function admin_sales_to_incoices()
    {
        $sepet_model          = new Sepet();
        $ps_model             = new Product_sepet();
        $adress_model         = new AcrFtrAdress();
        $fatura_product_model = new Fatura_product();
        $sepets               = $sepet_model->where('active', 1)->where('order_result', 2)->whereNotIn('user_id', [
            1,
            5,
            35746
        ])->where('sil', 0)->get();
        $fatura_model         = new Fatura();
        /*$fatura_model->where('guncel', 0)->orWhereNull('cinsi', null)->delete();
        $fatura_product_model->where('id', '!=', 0)->delete();
        exit();*/
        foreach ($sepets as $sepet_row) {
            $fatura_count = $fatura_model->where('order_id', $sepet_row->id)->count();
            if ($fatura_count < 1) {
                $adress_row         = $adress_model->where('active', 1)->where('user_id', $sepet_row->user_id)->with('city', 'county')->first();
                $orders             = $ps_model->where('sepet_id', $sepet_row->id)->with('product', 'acr_product', 'sepet')->get();
                $urun_names         = [];
                $total_vat          = [];
                $acr_fatura_product = [];
                foreach ($orders as $order) {
                    $urun_names [] = $order->product->product_name;
                    if (empty($order->product->collection) || $order->product->collection == '0.00') {
                        $kdv         = $order->product->kdv;
                        $ps_price    = self::sepet_total_price($order->id);
                        $fiyat       = round(((($ps_price * ((100) / (100 + $kdv)))) / $order->adet), 4);
                        $total_vat[] = ($order->product->price - ($order->product->price * $order->sepet->dis_rate)) * $kdv;
                    } else {
                        $kdv         = $order->product->collection_kdv;
                        $fiyat       = round(((($order->product->collection * ((100) / (100 + $kdv)))) / $order->adet), 4);
                        $total_vat[] = ($order->product->price - ($order->product->price * $order->sepet->dis_rate)) * $kdv;
                    }
                    $acr_fatura_product[] = [
                        'order_id'     => $sepet_row->id,
                        'name'         => $order->product->product_name,
                        'kdv'          => $order->adet * (0.18 * $fiyat),
                        'fiyat'        => $order->adet * $fiyat,
                        'toplam_fiyat' => $order->adet * ($fiyat + (0.18 * $fiyat)),
                        'adet'         => $order->adet,
                    ];
                }
                $fatura_data = [
                    'order_id'     => $sepet_row->id,
                    'invoice_name' => $adress_row->invoice_name,
                    'adress'       => $adress_row->adress . ' ' . $adress_row->county->name . '/' . $adress_row->city->name,
                    'tax_office'   => $adress_row->tax_office,
                    'tax_number'   => $adress_row->tax_number,
                    'tc'           => $adress_row->tc,
                    'tarih'        => $adress_row->updated_at,
                    'user_id'      => $adress_row->user_id,
                    'tel'          => $adress_row->tel,
                    'post_code'    => $adress_row->post_code,
                    'type'         => $adress_row->type,
                    'guncel'       => 1,
                    // '0 eski 1 güncel,
                    'fiyat'        => $sepet_row->price,
                    'fiyat_yazi'   => self::paraYazi($sepet_row->price)
                ];
                self::fatura_olustur($fatura_data, $acr_fatura_product); // sistem içinde tutulan faturalar
            }
        }
    }

    function paraYazi($money = '0.00')
    {
        $money = explode('.', $money);
        if (count($money) != 2)
            return false;
        $money_left  = $money['0'];
        $money_right = $money['1'];
        //DOKUZLAR
        if (strlen($money_left) == 9) {
            $i = (int)floor($money_left / 100000000);
            if ($i == 1)
                $l9 = "YÜZ";
            if ($i == 2)
                $l9 = "İKİ YÜZ";
            if ($i == 3)
                $l9 = "ÜÇ YÜZ";
            if ($i == 4)
                $l9 = "DÖRT YÜZ";
            if ($i == 5)
                $l9 = "BEŞ YÜZ";
            if ($i == 6)
                $l9 = "ALTI YÜZ";
            if ($i == 7)
                $l9 = "YEDİ YÜZ";
            if ($i == 8)
                $l9 = "SEKİZ YÜZ";
            if ($i == 9)
                $l9 = "DOKUZ YÜZ";
            if ($i == 0)
                $l9 = "";
            $money_left = substr($money_left, 1, strlen($money_left) - 1);
        }
        //SEKİZLER
        if (strlen($money_left) == 8) {
            $i = (int)floor($money_left / 10000000);
            if ($i == 1)
                $l8 = "ON";
            if ($i == 2)
                $l8 = "YİRMİ";
            if ($i == 3)
                $l8 = "OTUZ";
            if ($i == 4)
                $l8 = "KIRK";
            if ($i == 5)
                $l8 = "ELLİ";
            if ($i == 6)
                $l8 = "ATMIŞ";
            if ($i == 7)
                $l8 = "YETMİŞ";
            if ($i == 8)
                $l8 = "SEKSEN";
            if ($i == 9)
                $l8 = "DOKSAN";
            if ($i == 0)
                $l8 = "";
            $money_left = substr($money_left, 1, strlen($money_left) - 1);
        }
        //YEDİLER
        if (strlen($money_left) == 7) {
            $i = (int)floor($money_left / 1000000);
            if ($i == 1) {
                if ($i != "NULL") {
                    $l7 = "BİR MİLYON";
                } else {
                    $l7 = "MİLYON";
                }
            }
            if ($i == 2)
                $l7 = "İKİ MİLYON";
            if ($i == 3)
                $l7 = "ÜÇ MİLYON";
            if ($i == 4)
                $l7 = "DÖRT MİLYON";
            if ($i == 5)
                $l7 = "BEŞ MİLYON";
            if ($i == 6)
                $l7 = "ALTI MİLYON";
            if ($i == 7)
                $l7 = "YEDİ MİLYON";
            if ($i == 8)
                $l7 = "SEKİZ MİLYON";
            if ($i == 9)
                $l7 = "DOKUZ MİLYON";
            if ($i == 0) {
                if ($i != "NULL") {
                    $l7 = "MİLYON";
                } else {
                    $l7 = "";
                }
            }
            $money_left = substr($money_left, 1, strlen($money_left) - 1);
        }
        //ALTILAR
        if (strlen($money_left) == 6) {
            $i = (int)floor($money_left / 100000);
            if ($i == 1)
                $l6 = "YÜZ";
            if ($i == 2)
                $l6 = "İKİ YÜZ";
            if ($i == 3)
                $l6 = "ÜÇ YÜZ";
            if ($i == 4)
                $l6 = "DÖRT YÜZ";
            if ($i == 5)
                $l6 = "BEŞ YÜZ";
            if ($i == 6)
                $l6 = "ALTI YÜZ";
            if ($i == 7)
                $l6 = "YEDİ YÜZ";
            if ($i == 8)
                $l6 = "SEKİZ YÜZ";
            if ($i == 9)
                $l6 = "DOKUZ YÜZ";
            if ($i == 0)
                $l6 = "";
            $money_left = substr($money_left, 1, strlen($money_left) - 1);
        }
        //BEŞLER
        if (strlen($money_left) == 5) {
            $i = (int)floor($money_left / 10000);
            if ($i == 1)
                $l5 = "ON";
            if ($i == 2)
                $l5 = "YİRMİ";
            if ($i == 3)
                $l5 = "OTUZ";
            if ($i == 4)
                $l5 = "KIRK";
            if ($i == 5)
                $l5 = "ELLİ";
            if ($i == 6)
                $l5 = "ATMIŞ";
            if ($i == 7)
                $l5 = "YETMİŞ";
            if ($i == 8)
                $l5 = "SEKSEN";
            if ($i == 9)
                $l5 = "DOKSAN";
            if ($i == 0)
                $l5 = "";
            $money_left = substr($money_left, 1, strlen($money_left) - 1);
        }
        //DÖRTLER
        if (strlen($money_left) == 4) {
            $i = (int)floor($money_left / 1000);
            if ($i == 1) {
                if ($i != "") {
                    $l4 = "BİR BİN";
                } else {
                    $l4 = "BİN";
                }
            }
            if ($i == 2)
                $l4 = "İKİ BİN";
            if ($i == 3)
                $l4 = "ÜÇ BİN";
            if ($i == 4)
                $l4 = "DÖRT BİN";
            if ($i == 5)
                $l4 = "BEŞ BİN";
            if ($i == 6)
                $l4 = "ALTI BİN";
            if ($i == 7)
                $l4 = "YEDİ BİN";
            if ($i == 8)
                $l4 = "SEKZ BİN";
            if ($i == 9)
                $l4 = "DOKUZ BİN";
            if ($i == 0) {
                if ($i != "") {
                    $l4 = "BİN";
                } else {
                    $l4 = "";
                }
            }
            $money_left = substr($money_left, 1, strlen($money_left) - 1);
        }
        //ÜÇLER
        if (strlen($money_left) == 3) {
            $i = (int)floor($money_left / 100);
            if ($i == 1)
                $l3 = "YÜZ";
            if ($i == 2)
                $l3 = "İKİYÜZ";
            if ($i == 3)
                $l3 = "ÜÇYÜZ";
            if ($i == 4)
                $l3 = "DÖRTYÜZ";
            if ($i == 5)
                $l3 = "BEŞYÜZ";
            if ($i == 6)
                $l3 = "ALTIYÜZ";
            if ($i == 7)
                $l3 = "YEDİYÜZ";
            if ($i == 8)
                $l3 = "SEKİZYÜZ";
            if ($i == 9)
                $l3 = "DOKUZYÜZ";
            if ($i == 0)
                $l3 = "";
            $money_left = substr($money_left, 1, strlen($money_left) - 1);
        }
        //İKİLER
        if (strlen($money_left) == 2) {
            $i = (int)floor($money_left / 10);
            if ($i == 1)
                $l2 = "ON";
            if ($i == 2)
                $l2 = "YİRMİ";
            if ($i == 3)
                $l2 = "OTUZ";
            if ($i == 4)
                $l2 = "KIRK";
            if ($i == 5)
                $l2 = "ELLİ";
            if ($i == 6)
                $l2 = "ATMIŞ";
            if ($i == 7)
                $l2 = "YETMİŞ";
            if ($i == 8)
                $l2 = "SEKSEN";
            if ($i == 9)
                $l2 = "DOKSAN";
            if ($i == 0)
                $l2 = "";
            $money_left = substr($money_left, 1, strlen($money_left) - 1);
        }
        //BİRLER
        if (strlen($money_left) == 1) {
            $i = (int)floor($money_left / 1);
            if ($i == 1)
                $l1 = "BİR";
            if ($i == 2)
                $l1 = "İKİ";
            if ($i == 3)
                $l1 = "ÜÇ";
            if ($i == 4)
                $l1 = "DÖRT";
            if ($i == 5)
                $l1 = "BEŞ";
            if ($i == 6)
                $l1 = "ALTI";
            if ($i == 7)
                $l1 = "YEDİ";
            if ($i == 8)
                $l1 = "SEKİZ";
            if ($i == 9)
                $l1 = "DOKUZ";
            if ($i == 0)
                $l1 = "";
            $money_left = substr($money_left, 1, strlen($money_left) - 1);
        }
        //SAĞ İKİ
        if (strlen($money_right) == 2) {
            $i = (int)floor($money_right / 10);
            if ($i == 1)
                $r2 = "ON";
            if ($i == 2)
                $r2 = "YİRMİ";
            if ($i == 3)
                $r2 = "OTUZ";
            if ($i == 4)
                $r2 = "KIRK";
            if ($i == 5)
                $r2 = "ELLİ";
            if ($i == 6)
                $r2 = "ALTMIŞ";
            if ($i == 7)
                $r2 = "YETMİŞ";
            if ($i == 8)
                $r2 = "SEKSEN";
            if ($i == 9)
                $r2 = "DOKSAN";
            if ($i == 0)
                $r2 = "SIFIR";
            $money_right = substr($money_right, 1, strlen($money_right) - 1);
        }
        //SAĞ BİR
        if (strlen($money_right) == 1) {
            $i = (int)floor($money_right / 1);
            if ($i == 1)
                $r1 = "BİR";
            if ($i == 2)
                $r1 = "İKİ";
            if ($i == 3)
                $r1 = "ÜÇ";
            if ($i == 4)
                $r1 = "DÖRT";
            if ($i == 5)
                $r1 = "BEŞ";
            if ($i == 6)
                $r1 = "ALTI";
            if ($i == 7)
                $r1 = "YEDİ";
            if ($i == 8)
                $r1 = "SEKİZ";
            if ($i == 9)
                $r1 = "DOKUZ";
            if ($i == 0)
                $r1 = "";
            $money_right = substr($money_right, 1, strlen($money_right) - 1);
        }
        return @$l9 . " " . @$l8 . " " . @$l7 . " " . @$l6 . " " . @$l5 . " " . @$l4 . " " . @$l3 . " " . @$l2 . " " . @$l1 . " TÜRK LİRASI " . @$r2 . " " . @$r1 . " KURUŞ";
    }

    function invers_son_aktif_tarih($ay = null, $lisans_bitis)
    {
        $ekle = $lisans_bitis - time();

        if ($ekle < 0) {
            $ekle = 0;
        }
        $odemeZaman = $ekle - mktime(0, 0, 0, date('m') + $ay, date('d'), date('Y'));
        return date('Y-m-d H:i:s', $odemeZaman);
    }

    function orders_deactive(Request $request, $order_id = null)
    {
        $order_id            = empty($order_id) ? $request->input('order_id') : $order_id;
        $sepet_model         = new Sepet();
        $ps_model            = new Product_sepet();
        $user_model          = new AcrUser();
        $sepet               = $sepet_model->find($order_id);
        $sepet->active       = 0;
        $sepet->order_result = 3;
        $sepet->save();
        $sepet_row = $sepet_model->where('id', $order_id)->first();
        $orders    = $ps_model->where('sepet_id', $order_id)->get();
        foreach ($orders as $order) {
            if ($order->type == 2) {
                $user               = $user_model->find($sepet_row->user_id);
                $user_row           = $user_model->where('id', $sepet_row->user_id)->first();
                $user->lisans_durum = 0;
                if (strtotime($user_row->lisans_bitis) < time()) {
                    $lisans_bitis = time();
                } else {
                    $lisans_bitis = strtotime($user_row->lisans_bitis);
                }
                $user->lisans_bitis = self::invers_son_aktif_tarih($order->lisans_ay, $lisans_bitis);
                $user->save();
            }
        }

    }

}