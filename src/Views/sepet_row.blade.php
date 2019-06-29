<?php

$total_price = [];
foreach ($products as $product) {
//$ay_baz    = 7 > date('n') ? 7 - date('n') : 19 - date('n');
// $ay_lisans = $product->created_at == $product->updated_at ? $ay_baz : $product->lisans_ay;
$sepet_id = $product->sepet_id;
$price = $product->product->price * $product->adet * $product->lisans_ay;
if (in_array($product->product_id, $promo_user_ids) && $promo_user[$product->product_id]['min_ay'] <= $product->lisans_ay && $promo_user[$product->product_id]['min_adet'] <= $product->adet) {
    $dis_price = $spc->price_set($product) - $promo_user[$product->product_id]['price'];
} else {
    $dis_price = $spc->price_set($product);
}
$dis_rate = $spc->dis_rate($price, $dis_price);
if ($product->dis_rate != $dis_rate) {
    $ps_model->where('id', $sepet_id)->update(['dis_rate' => $dis_rate]);
}
$type = $product->product->type == 1 ? 'Lisans' : 'Ürün' ?>
<tr class="sepet_row" id="sapet_row_{{$product->id}}">
    <td>{{$product->product->product_name }}</td>
    <td>{{$type }}</td>
    <td>
        <div class="col-md-6 col-xs-12">
            @if($product->product->id ==1282)
                1
            @else

                <input class="form-control" onchange="sepet_adet_guncelle({{$product->id}})" onkeyup="sepet_adet_guncelle({{$product->id}} )" style="width: 70px;" id="sepet_adet_{{$product->id}}" value="{{$product->adet}}"/>
            @endif

        </div>
        @if($product->product->id ==1282)
        @else
            @if ($product->product->type == 1)
                <div class="col-md-6 col-xs-12">
                    <div class="col-md-6 col-xs-12">Kaç Aylık</div>
                    <div class="col-md-6 col-xs-12">
                        <input size="3" class="form-control" onchange="sepet_lisans_ay_guncelle({{$product->id}})" onkeyup="sepet_lisans_ay_guncelle({{$product->id}})" style="width: 70px;" id="sepet_lisans_ay_{{$product->id}}"
                               value="{{$product->lisans_ay}}"/>
                    </div>
                </div>
            @endif
        @endif
    </td>
    <td>
        {{$product->product->price}}₺
    </td>
    <td>
        @if ($price > $dis_price)
            <?php
            $total_price[] = round($dis_price, 2);?>
            <span id="product_dis_{{$product->id}}"><strike style="color: #be3946; font-size: 9pt;">{{round($price, 2)}} </strike>{!! $spc->discount($price, $dis_price) !!} <br></span>
            <span id="product_price_{{$product->id}}" style="color: #2d7c32; font-size: 12pt;">{{round($dis_price, 2)}}₺</span>
        @else
            <?php $total_price[] = round($price, 2); ?>
            <span id="product_price_{{$product->id}} " style="color: #2d7c32; font-size: 12pt;">{{round($price, 2)}}₺</span>
        @endif
    </td>
    <td style="text-align: right"><span style="font-size:14pt; padding-top: 6px; cursor:pointer;" onclick="sepet_delete({{$product->id}})" class="fa fa-trash"></span></td>
</tr>
<?php }?>
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td id="acr_sepet_total_price" colspan="2">{{array_sum($total_price)}}₺</td>
</tr>
