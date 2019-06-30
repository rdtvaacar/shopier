@extends('acr_shopier.index')
@section('header')
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
@stop
@section('acr_shopier')
    <section class="content">
        <div class="row">
            <div class=" col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">Siparişler</div>
                    <div class="box-body">
                        <form action="/acr/ftr/admin/siparis/faturalar" method="post">
                            {{csrf_field()}}
                            <div style="width: 600px; float: left;" class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input value="{{$tarih_veri}}" name="tarih" type="text" class="form-control pull-right"
                                       id="reservation">
                            </div>
                            <button type="submit" style="float: left;" class="btn btn-primary btn-sm">FİLTRELE</button>
                        </form>
                        <div class="btn btn-info btn-sm"
                             onclick="popup('/acr/ftr/admin/fatura/yazdir?tarih_ilk={{$tarih_ilk}}&tarih_son={{$tarih_son}}')">
                            Fatura Yazdır
                        </div>
                        <div style="clear:both;"></div>
                        <table width="100%" id="data_table" class="table table-striped">
                            <thead>
                            <tr>
                                <th>SI. NO</th>
                                <th>ID</th>
                                <th>Tarih</th>
                                <th>Fatura İsmi</th>
                                <th>User_id</th>
                                <th>User</th>
                                <th>Ürünler</th>
                                <th>E-Fat Gönder</th>
                                <th>Fiyat</th>
                                <th>Oluşturma Tarihi</th>
                            </tr>


                            </thead>
                            <tbody id="sepet_tbody">
                            @foreach ($faturalar as $key=> $fatura)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$fatura->id}}</td>
                                    <td>{{$fatura->tarih}}</td>
                                    <td>{{$fatura->invoice_name}}</td>
                                    <td>{{$fatura->user->id}}</td>
                                    <td>{{$fatura->user->name}}<br>
                                        {{$fatura->user->$email}}<br>
                                        {{$fatura->user->tel}}</td>
                                    <td>
                                        <a target="_blank" class="btn btn-warning btn-xs"
                                           href="/acr/ftr/admin/e_arsive/create?fatura_id={{$fatura->id}}">
                                            E-Fat Gönder</a>
                                    </td>
                                    <td>
                                        @if(empty($fatura->cinsi))
                                            <table class="table">
                                                <tr>
                                                    <td>Adet</td>
                                                    <td>Fiyat</td>
                                                    <td>KDV</td>
                                                    <td>Toplam</td>
                                                </tr>
                                                @foreach ($fatura->products as $e_product)
                                                    <tr>
                                                        <td>{{$e_product->adet}}</td>
                                                        <td>{{$e_product->fiyat}}</td>
                                                        <td>{{$e_product->kdv}}</td>
                                                        <td>{{$e_product->toplam_fiyat  }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @else
                                            {{$fatura->cinsi}}
                                        @endif
                                    </td>
                                    <td>{{$fatura->fiyat}}</td>
                                    <td>{{$fatura->created_at}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <table class="table">
                            <tr>
                                <td>Fiyat</td>
                                <td>KDV</td>
                                <td>TOPLAM</td>
                            </tr>
                            <tr>
                                <td>{{round($fiyat,2)}}</td>
                                <td>{{round($kdv,2)}}</td>
                                <td>{{round($ciro,2)}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('footer')
    <script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="/plugins/datepicker/locales/bootstrap-datepicker.tr.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
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
        $('#reservation').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
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
                    $('#sepet_lisans_ay_tik_' + sepet_id).toggle(200);
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

        function order_active(id) {
            var order_id = id;
            if ($('#order_input_' + id).is(':checked')) {

                $.ajax({
                    type: 'post',
                    url: '/acr/ftr/order/active/admin',
                    data: 'order_id=' + order_id,
                    success: function () {
                    }
                });
            } else {
                $.ajax({
                    type: 'post',
                    url: '/acr/ftr/order/deactive',
                    data: 'order_id=' + order_id,
                    success: function () {
                    }
                });
            }
        }
        function popup(url) {
            window.open(url, "popupwindowname", "toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no, width=1050,height=750,left=200 ,top=200");
        }
    </script>
@stop