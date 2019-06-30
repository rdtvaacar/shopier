@extends('acr_shopier.index')
@section('header')
    <link rel="stylesheet" href="/css/acr_shopier/sepet.css">
@stop
@section('acr_shopier')
    <section class="content">
        <div class="row">
            {!!$msg!!}
            <div class=" col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border"><?php  echo $sepet_nav; ?></div>
                    <div class="box-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="40%">Ürün</th>
                                <th>Türü</th>
                                <th>Adet</th>
                                <th>B.Fiyat</th>
                                <th>Toplam</th>
                                <th style="text-align: right">Sil</th>
                            </tr>
                            </thead>
                            <tbody id="sepet_tbody"><?php echo $sepet_row; ?></tbody>
                            <tfoot>
                            <tr>
                                <td><a style="float: left;" class="btn btn-lg btn-warning" href="/acr/ftr/card/adress<?php echo $order_link?>">ADRES BİLGİLERİ <span class="fa fa-angle-double-right"></span></a></td>
                                <td colspan="3">
                                    <div style="font-size: 9pt; float: right; cursor:pointer;" onclick="sepet_delete_all()">Tümünü Sil</div>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('footer')
    <script>


        function sepet_adet_guncelle(sepet_id) {
            var adet = $('#sepet_adet_' + sepet_id).val();
            $.ajax({
                type: 'post',
                url: '/acr/ftr/product/sepet/sepet_adet_guncelle',
                data: 'sepet_id=' + sepet_id + '&adet=' + adet,
                success: function (veri) {
                    $('.sepet_count').html(veri);
                    $.ajax({
                        type: 'post',
                        url: '/acr/ftr/product/sepet/sepet_total_price',
                        data: 'sepet_id=' + sepet_id,
                        success: function (msg) {
                            $('#product_price_' + sepet_id).html(msg + '₺');
                            $.ajax({
                                type: 'post',
                                url: '/acr/ftr/product/sepet/product_sepet_total_price',
                                data: 'sepet_id=' + sepet_id,
                                success: function (msg) {
                                    $('#acr_sepet_total_price').html(msg + '₺');
                                    $('#product_dis_' + sepet_id).hide();

                                }
                            });
                        }
                    });

                }
            });
        }
        function sepet_lisans_ay_guncelle(sepet_id) {

            var lisans_ay = $('#sepet_lisans_ay_' + sepet_id).val();
            $.ajax({
                type: 'post',
                url: '/acr/ftr/product/sepet/sepet_lisans_ay_guncelle',
                data: 'sepet_id=' + sepet_id + '&lisans_ay=' + lisans_ay,
                success: function () {
                    $.ajax({
                        type: 'post',
                        url: '/acr/ftr/product/sepet/sepet_total_price',
                        data: 'sepet_id=' + sepet_id,
                        success: function (msg) {
                            $('#product_price_' + sepet_id).html(msg + '₺');
                            $.ajax({
                                type: 'post',
                                url: '/acr/ftr/product/sepet/product_sepet_total_price',
                                data: 'sepet_id=' + sepet_id,
                                success: function (msg) {
                                    $('#acr_sepet_total_price').html(msg + '₺');
                                    $('#product_dis_' + sepet_id).hide();
                                }
                            });
                        }
                    });
                }
            });
        }

        function sepet_delete(sepet_id) {
            $.ajax({
                type: 'post',
                url: '/acr/ftr/product/sepet/delete',
                data: 'sepet_id=' + sepet_id,
                success: function (veri) {
                    $('.sepet_count').html(veri);
                    $('#sapet_row_' + sepet_id).fadeOut(400);
                }
            });
        }
        function sepet_delete_all() {
            $.ajax({
                type: 'post',
                url: '/acr/ftr/product/sepet/delete_all',
                success: function (veri) {
                    $('.sepet_count').html(0);
                    $('.sepet_row').fadeOut(400);
                }
            });
        }
    </script>
@stop