@extends('acr_shopier.index')
@section('header')
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
@stop
@section('acr_shopier')
    <section class="content">
        <div class="row">
            <div class=" col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">Siparişlerim</div>
                    <div class="box-body">
                        <table width="100%" id="data_table" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Sipariş NO:</th>
                                <th>Ödeme Türü</th>
                                <th>Fiyat</th>
                                <th>Oluşturma Tarihi</th>
                                <th>Ödeme Sonucu</th>
                            </tr>
                            </thead>
                            <tbody id="sepet_tbody">
                            <?php foreach ($orders as $order) {
                            $payment_type = $order->payment_type == 1 ? '<span style="color: #3a7c67;">HAVALE / EFT</span>' : '<span style="color: #7c3108;">KREDİ KARTI</span>';
                            switch ($order->order_result) {
                                case 1:
                                    $order_result = '<span style="color: #7c3422;">ÖDENMEDİ</span> <a class="btn btn-xs btn-warning" href="/acr/ftr/card/payment?order_id=' . $order->id . '">ŞİMDİ ÖDE</a>';
                                    break;
                                case 2:
                                    $order_result ='<span style="color: #357c14;"> ÖDENDİ</span>';
                                    break;
                                case 3:
                                    $order_result ='<span class="text-orange">İPTAL EDİLDİ</span>';
                                    break;
                            }
                            ?>

                            <tr>
                                <td>{{$order->id}}</td>
                                <td><?php echo $payment_type ?></td>
                                <td>{{$order->price}}</td>
                                <td>{{$order->created_at}}</td>
                                <td><?php echo $order_result?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('footer')
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
        $('#data_table').DataTable({
            "paging"      : true,
            "lengthChange": false,
            "searching"   : true,
            "ordering"    : true,
            "info"        : true,
            "autoWidth"   : true,
            "language"    : {
                "sProcessing" : "İşleniyor...",
                "lengthMenu"  : "Sayfada _MENU_ satır gösteriliyor",
                "zeroRecords" : "Gösterilecek sonuç yok.",
                "info"        : "Toplam _PAGES_ sayfadan _PAGE_. sayfa gösteriliyor",
                "infoEmpty"   : "Gösterilecek öğe yok",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "search"      : "Arama yap",
                "oPaginate"   : {
                    "sFirst"   : "İlk",
                    "sPrevious": "Önceki",
                    "sNext"    : "Sonraki",
                    "sLast"    : "Son"
                }

            }
        });
        function sepet_adet_guncelle(sepet_id) {
            var adet = $('#sepet_adet_' + sepet_id).val();
            $.ajax({
                type   : 'post',
                url    : '/acr/ftr/product/sepet/sepet_adet_guncelle',
                data   : 'sepet_id=' + sepet_id + '&adet=' + adet,
                success: function (veri) {
                    $('.sepet_count').html(veri);
                    $.ajax({
                        type   : 'post',
                        url    : '/acr/ftr/product/sepet/sepet_total_price',
                        data   : 'sepet_id=' + sepet_id,
                        success: function (msg) {
                            $('#product_price_' + sepet_id).html(msg + '₺');
                            $.ajax({
                                type   : 'post',
                                url    : '/acr/ftr/product/sepet/product_sepet_total_price',
                                data   : 'sepet_id=' + sepet_id,
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
                type   : 'post',
                url    : '/acr/ftr/product/sepet/sepet_lisans_ay_guncelle',
                data   : 'sepet_id=' + sepet_id + '&lisans_ay=' + lisans_ay,
                success: function () {
                    $.ajax({
                        type   : 'post',
                        url    : '/acr/ftr/product/sepet/sepet_total_price',
                        data   : 'sepet_id=' + sepet_id,
                        success: function (msg) {
                            $('#product_price_' + sepet_id).html(msg + '₺');
                            $.ajax({
                                type   : 'post',
                                url    : '/acr/ftr/product/sepet/product_sepet_total_price',
                                data   : 'sepet_id=' + sepet_id,
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
                type   : 'post',
                url    : '/acr/ftr/product/sepet/delete',
                data   : 'sepet_id=' + sepet_id,
                success: function (veri) {
                    $('.sepet_count').html(veri);
                    $('#sapet_row_' + sepet_id).fadeOut(400);
                }
            });
        }
        function sepet_delete_all() {
            $.ajax({
                type   : 'post',
                url    : '/acr/ftr/product/sepet/delete_all',
                success: function (veri) {
                    $('.sepet_count').html(0);
                    $('.sepet_row').fadeOut(400);
                }
            });
        }
    </script>
@stop