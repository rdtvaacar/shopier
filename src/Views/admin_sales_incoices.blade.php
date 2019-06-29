@extends('acr_ftr.index')
@section('header')
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
@stop
@section('acr_ftr')
    <section class="content">
        <div class="row">
            <div class=" col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">Faturalar
                        <div onclick="ftrModal()" class="btn btn-primary btn-sm" style="float: right;">Yeni Ekle</div>
                    </div>
                    <div class="box-body">
                        <table width="100%" id="data_table" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Sipariş NO:</th>
                                <th>İsim</th>
                                <th>Fiyat</th>
                                <th>KDV</th>
                                <th>TOPLAM</th>
                                <th>Oluşturma Tarihi</th>
                                <th>Adres</th>
                                <th>İşlem</th>

                            </tr>
                            </thead>
                            <tbody id="sepet_tbody">
                            <?php
                            $toplam_fiyat = [];
                            $toplam_kdv = [];
                            $toplam_ciro = [];
                            foreach ($orders->items as $order) {
                            $order = (Object)$order;
                            ?>
                            <tr>
                                <td>{{$order->order_id}}</td>
                                <td>{{$order->invoice_name}}<br>
                                    {{$order->tel}}</td>
                                <td>{{$toplam_fiyat[] = $order->gross_total}}</td>
                                <td>{{$toplam_kdv[] = $order->total_vat}}</td>
                                <td>{{$toplam_ciro[] = $order->total_paid}}</td>
                                <td>{{$order->created_at}}</td>
                                <td>{{$order->billing_address}}</td>
                                <td>
                                    <div onclick="invoice_delete({{$order->id}})" class="btn btn-warning btn-sm"
                                         title="Faturayı İptal Et">İPTL
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <table style="width: 320px" class="table">
                            <tr>
                                <th>Toplam Kazanılan</th>
                                <th>KDV</th>
                                <th>Toplam Ciro</th>
                            </tr>
                            <tr>
                                <td>{{array_sum($toplam_fiyat)}} </td>
                                <td>{{array_sum($toplam_kdv)}}</td>
                                <td>{{array_sum($toplam_ciro)}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="ftrModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true"><img src="/icon/close48.png"></span></button>
                    <h4 class="modal-title" id="myModalLabel">Yeni Fatura Ekle</h4>
                </div>
                <div id="menuModalIcerik" class="modal-body">
                    <label>Fatura Adı</label>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('footer')
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
        $('#data_table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "language": {
                "sProcessing": "İşleniyor...",
                "lengthMenu": "Sayfada _MENU_ satır gösteriliyor",
                "zeroRecords": "Gösterilecek sonuç yok.",
                "info": "Toplam _PAGES_ sayfadan _PAGE_. sayfa gösteriliyor",
                "infoEmpty": "Gösterilecek öğe yok",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "search": "Arama yap",
                "oPaginate": {
                    "sFirst": "İlk",
                    "sPrevious": "Önceki",
                    "sNext": "Sonraki",
                    "sLast": "Son"
                }

            }
        });

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

        function ftrModal() {
            $('#ftrModal').modal('show');
        }

        function invoice_delete(id) {
            if (confirm('Silmek istediğinizden emin misiniz.') == true) {
                $.ajax({
                    type: 'delete',
                    url: '/acr/ftr/admin/sales_invoices',
                    data: 'id=' + id,
                    success: function () {
                        $('#' + id).fadeOut(400);
                    }
                });
            }

        }
    </script>
@stop