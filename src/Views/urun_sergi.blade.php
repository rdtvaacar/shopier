<style>
    .saga_yasla {
        position: absolute;
        right: 10px;
    }

    .sola_cek:hover {
        position: absolute;
        right: 8px;
    }

</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border alert alert-info">{{$kat->kat_isim}}</div>
                <div class="box-body">
                    @foreach($kat->products as $product)
                        @if(!empty($product->my_product))
                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <div style="width: 100%; text-align: center" class="img-thumbnail">
                                    <div class="box-header with-border">{{$product->product_name}}</div>
                                    <div style="margin-right: auto; margin-bottom: 10px; margin-left: auto;" class="img-thumbnail">
                                        <a href="/acr/ftr/product/detail?product_id={{$product->id}}"> <img
                                                    src="https://eticaret.webuldum.com/acr_files/{{@$product->file->acr_file_id}}/thumbnail//{{@$product->file->file_name}}.{{@$product->file->file_type}}"/>
                                        </a>
                                        <div style=" width: 50px;  position: absolute; right: -10px; top:50px;">
                                            <a target="_blank" class="saga_yasla sola_cek" href="https://www.facebook.com/sharer/sharer.php?u={{$web}}/acr/ftr/product/detail?product_id={{$product->id}}"><img width="40"
                                                                                                                                                                                                                src="/icon/bF.png"></a>
                                            <br><br><br>
                                            <a target="_blank" class="saga_yasla sola_cek"
                                               href="http://twitter.com/intent/tweet?text=1.sinifokumayazmaplani.rar&amp;url={{$web}}/acr/ftr/product/detail?product_id={{$product->id}}/&amp;via=oevrak Önlük Baskı">
                                                <img width="40" src="/icon/bT.png">
                                            </a>
                                            <br><br><br>

                                            <a target="_blank" class="saga_yasla sola_cek"
                                               href="http://pinterest.com/pin/create/link/?url={{$web}}/acr/ftr/product/detail?product_id={{$product->id}}&amp;description=1">
                                                <img width="40" src="/icon/bP.png">
                                            </a>
                                        </div>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div style="float: left; width: 100px; font-size: 22pt; padding: 2px; " class="alert alert-warning">{{$product->price}}<span style="font-size: 12pt;">₺</span></div>
                                    <a style="float: right" class="btn btn-success btn-lg" href="/acr/ftr/product/detail?product_id={{$product->id}}">Detay>></a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
