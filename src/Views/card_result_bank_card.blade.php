@extends('acr_ftr.index')
@section('header')
    <link rel="stylesheet" href="/css/acr_ftr/sepet.css">
    <link rel="stylesheet" href="/plugins/iCheck/all.css">
@stop
@section('acr_ftr')
    <section class="content">
        <div class="row">
            <div class=" col-md-5">
                <div class="box box-warning">
                    <div class="box-header with-border"><?php echo $sepet_nav ?>
                    </div>
                    <div class="box-body">
                        <?php echo $odemeForm ?>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h2 class="page-header">
                            <i class="fa fa-globe"></i> SİPARİŞ NUMARANIZ : <span
                                    class="text-red"><b><?php echo $siparis_id = empty($siparis->id) ? 0 : $siparis->id; ?></b></span>
                            <small class="pull-right">Tarih: {{date('d/m/Y',strtotime($siparis->updated_at))}}</small>
                        </h2>
                    </div>
                    <div class="box-body">
                        <div class="row invoice-info">
                            <div class="col-sm-5 invoice-col">
                                Satıcı
                                <address>
                                    <strong>{{@$company->name}}</strong><br>
                                    {{@$company->adress}}<br>
                                    {{@$company->county}} / {{$company->city}}<br>
                                    Phone: {{@$company->tel}}<br>
                                    Email: {{@$company->email}}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-5 invoice-col">
                                Alıcı
                                <address>
                                    <strong>{{@$user_adress->type ==2 ? @$user_adress->company:@$user_adress->invoice_name}}</strong><br>
                                    {{@$user_adress->adress}}<br>
                                    {{@$user_adress->county->name}} / {{@$user_adress->city->name}}<br>
                                    Telefon: {{@$user_adress->tel}}<br>
                                    Email: {{Auth::user()->email}}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-2 invoice-col">

                                <b>Sipariş NO:</b> <?php echo $siparis_id = empty($siparis->id) ? 0 : $siparis->id; ?>
                                <br>
                                <b>Tarih:</b> {{date('d/m/Y',strtotime($siparis->updated_at))}}<br>
                                <b>Hesap ID'niz:</b> {{Auth::user()->id}}
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <!-- Table row -->
                        <div class="row">
                            <div class="col-xs-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sira</th>
                                        <th>Product</th>
                                        <th>Ürün No #</th>
                                        <th>Adet</th>
                                        <th>Ay</th>
                                        <th>Birim Fiyatı</th>
                                        <th>İndirim Oranı</th>
                                        <th>Fiyat</th>
                                        <th>KDV</th>
                                        <th>Toplam</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($ps as $key=> $pss)
                                        <?php
                                        if (in_array($pss->product_id, $promo_user_ids) && $promo_user[$pss->product_id]['min_ay'] <= $pss->lisans_ay && $promo_user[$pss->product_id]['min_adet'] <= $pss->adet) {
                                            $indirim = $promo_user[$pss->product_id]['price'];
                                        } else {
                                            $indirim = 0;
                                        }
                                        $toplam = $sepetController->price_set($pss, $indirim);
                                        $fiyat = $toplam * 100 / ($pss->product->kdv + 100);
                                        $toplamKdv[] = $toplam - $fiyat;
                                        $araToplam[] = $fiyat;

                                        ?>
                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td>{{$pss->product->product_name}}</td>
                                            <td>
                                                <span class="text-danger"><b>{{$u_kods[] = $pss->product->id}}</b></span>
                                            </td>
                                            <td>{{$pss->adet}}</td>
                                            <td>{{$pss->lisans_ay}}</td>
                                            <td>
                                                <?php echo empty($pss->product->dis_price) || ($pss->adet == 1 && $pss->lisans_ay == 1) || $pss->product->dis_price == 0 || $pss->product->price == $pss->product->dis_price ? $pss->product->price : '<strike style="font-size: 10pt;">' . $pss->product->price . ' </strike> ' . $pss->product->dis_price ?>
                                                ₺
                                            </td>
                                            <td>%{{round($pss->dis_rate,2) * 100}}</td>
                                            <td>
                                                {{round($fiyat,2)}}
                                                ₺
                                            </td>
                                            <td><span style="font-size:8pt;"
                                                      class="text-muted">%{{$pss->product->kdv}} </span>{{round($toplam - $fiyat,2)}}
                                                ₺
                                            </td>
                                            <td>{{round($toplam,2)}}₺</td>
                                        </tr>
                                    @endforeach
                                    @if($indirim>0)
                                        <tr>
                                            <td colspan="10"><span class="text-red">{{$indirim}}₺ değerindeki indirim promosyonunuz otomatik olarak tanımlandı.</span></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->

                        <div class="row">
                            <!-- accepted payments column -->
                            <div class="col-xs-6">
                                <p class="lead">Ödeme Yöntemi:</p>
                                Kredi Kartı
                                </p>
                            </div>
                            <!-- /.col -->
                            <div class="col-xs-6">
                                <p class="lead">Toplam Alış-veriş
                                    Miktarı {{date('d/m/Y',strtotime($siparis->updated_at))}}</p>

                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th style="width:50%">Ara Toplam:</th>
                                            <td>{{round(array_sum($araToplam),2)}}₺</td>
                                        </tr>
                                        <tr>
                                            <th>KDV</th>
                                            <td>{{round(array_sum($toplamKdv),2)}}₺</td>
                                        </tr>
                                        <tr>
                                            <th>Toplam:</th>
                                            <td>
                                                {{-- <strike style="font-size: 10pt;">{{round(array_sum($araToplam) + array_sum($toplamKdv),2)}}</strike>--}}
                                                <span style="font-size: 14pt;" class="text-red">{{round((array_sum($araToplam) + array_sum($toplamKdv)),2)}}
                                                    ₺</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
