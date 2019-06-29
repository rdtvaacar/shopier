<?php
//  dd($products);

foreach ($products as $key =>$product) {
if(!empty($product->product)) {
?>
<div style="min-height:780px;" class="col-md-4 all_categories
                            @foreach($product->product->u_kats as $u_kat)
        kat_{{@$u_kat->id }}
@endforeach">
    <div class="price-col2">
        <div class="title-2"><?php echo $product->product->product_name; ?></div>
        <div class="col-md-12" style="text-align: center;">
            @if(!empty($product->product->file))
                <a href="/acr/ftr/product/detail?product_id={{$product->product->id}}"> <img class="img-rounded"
                                                                                             style="cursor:pointer; margin-top: 10px;"
                                                                                             src="https://eticaret.webuldum.com/acr_files/{{$product->product->file->acr_file_id}}/thumbnail/{{$product->product->file->file_name}}.{{$product->product->file->file_type}}"/>
                </a>
                <hr style="padding: 0;">
            @endif
        </div>
        <ul class="peice-list">
            <?php  //dd($product->attributes);
            foreach ($product->product->attributes as $attribute) { ?>
            <li>
                        <span style="cursor:pointer;" onclick="urunGoster(<?php echo $attribute->id ?>,<?php echo $product->id ?>)"><?php echo $attribute->att_name ?> <span
                                    class="glyphicon glyphicon-question-sign"></span></span>
            </li>
            <?php } ?>
            <li class="pack-price">
                        <span>
                            <?php
                            if ($product->product->type == 1) {?>
                            <?php echo $product->product->price ?>
                            <sub> ₺  /Ay </sub>
                            <?php }else { ?>
                            <?php echo $product->product->price ?> <sub> ₺ / Adet</sub>
                            <?php }
                            ?>
                        </span>
                <br>
                <?php if ($product->product->dis_price && ($product->product->dis_moon > 0 || $product->product->dis_person > 0)) {
                    echo '%' . $product->product->max_dis . ' varan indirim ';
                } ?>
            </li>

            <li style="text-align: center">
                <a href="/acr/ftr/product/detail?product_id=<?php echo $product->product->id ?> " class="btn btn-success  ">DETAYLI İNCELE</a>
            <!--<p><a href="/acr/ftr/card/sepet?product_id=<?php echo $product->product->id ?> " class="btn btn-success  ">SATIN AL</a>
                                                <button onclick="sepete_ekle(<?php echo $product->product->id ?>)" class="btn bg-orange margin">SEPETE EKLE</button>
                                            </p>-->
                <hr>
                <a class="text-yellow" href="/acr/ftr/card/sepet">Sepete Git (<span class="text-aqua sepet_count" style="font-size: 12pt;"><?php echo $sepet_count ?></span>)</a>
            </li>
        </ul>
    </div>
</div>
<?php }
}
?>