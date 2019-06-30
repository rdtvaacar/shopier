@extends('acr_shopier.index')
@section('acr_shopier')
    <section class="content">
        <div class="row">
            <div class=" col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">BAŞARISIZ ÖDEME</div>
                    <div class="box-body" style="text-align: center">
                        <img src="/icon/hata.png"/>
                        <div class="alert alert-danger" style="text-align: center; font-size: 16pt;">
                            Ödemeniz gerçekleşmemiş lütfen tekrar deneyiniz. <a class="btn btn-warning" href="/acr/ftr/card/payment?order_id={{$order_id}}">Tekrar Dene</a> için tıklayınız.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
