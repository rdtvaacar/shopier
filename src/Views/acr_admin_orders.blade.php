@extends('acr_ftr.index')
@section('header')
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
@stop
@section('acr_ftr')
    <section class="content">
        <div class="row">
            <div class=" col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">Siparişler</div>
                    <div class="box-body">
                        <table width="100%" id="data_table" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Son Güncelleme</th>
                                <th>S. NO</th>
                                <th>Fatura</th>
                                <th>Onayla/İptal</th>
                                <th>Ödeme Sonucu</th>
                                <th>UserID</th>
                                <th>Email</th>
                                <th>Ürünler</th>
                                <th>Ödeme Türü</th>
                                <th>Fiyat</th>
                                <th>Oluşturma Tarihi</th>
                                <th>FAT</th>
                            </tr>
                            </thead>
                            <tbody id="sepet_tbody">
                            <?php foreach ($orders as $order) {
                            $payment_type = $order->payment_type == 1 ? '<span style="color: #3a7c67;">HAVALE / EFT</span>' : '<span style="color: #7c3108;">KREDİ KARTI</span>';
                            $order_result = $order->order_result == 1 ? '
<span style="color: #7c3422;">ÖDENMEDİ</span>
' : '<span style="color:  #357c14;"> ÖDENDİ</span>';
                            ?>
                            <tr>
                                <td>{{$order->updated_at}}</td>
                                <td>{{$order->id}}</td>
                                <td>
                                    <input onclick="fatura_active(<?php echo $order->id; ?>)" id="fatura_input_{{$order->id}}"
                                           <?php  echo $order->fatura_active == 1 ? 'checked' : 0; ?> style="width: 32px; height: 32px; cursor:pointer;"
                                           name="siparis"
                                           value="{{$order->id}}"
                                           type="checkbox"/>
                                </td>
                                <td>
                                    <input onclick="order_active(<?php echo $order->id; ?>)" id="order_input_{{$order->id}}"
                                           <?php  echo $order->active == 1 ? 'checked' : 0; ?> style="width: 32px; height: 32px; cursor:pointer;"
                                           name="siparis"
                                           value="{{$order->id}}"
                                           type="checkbox"/>
                                </td>
                                <td><?php echo $order_result?></td>
                                <td>{{@$order->user->id}}</td>
                                <td>{{@$order->user->name}}<br>
                                    {{@$order->adress->tc}}/{{@$order->user->tc}}<br>
                                    {{@$order->user->$email}}<br>
                                    {{@$order->user->tel}}</td>
                                <td><?php echo $payment_type ?></td>
                                <td>
                                    <table class="table">
                                        <tr>
                                            <td>Adet</td>
                                            <td>Ay</td>
                                            <td>Ürün</td>
                                        </tr>
                                        @foreach ($order->products as $e_product)
                                            <tr>
                                                <td>{{$e_product->adet}}</td>
                                                <td>{{@$e_product->lisans_ay}}</td>
                                                <td>
                                                    {{@$e_product->product->product_name}}
                                                    <?php $bagli_uruns = json_decode($e_product->product_ids);
                                                    ?>
                                                    @if(!empty($bagli_uruns))
                                                        <ol>
                                                            @foreach ($bagli_uruns as $urun_id)
                                                                @if($urun_id !=1282)
                                                                    <li>
                                                                        {{$urun[$urun_id]->product_name}}

                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ol>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        @foreach ($order->notes as $note)
                                            <tr>
                                                <td class="text-red" colspan="3">{{$note->name}}</td>

                                            </tr>
                                        @endforeach
                                    </table>

                                </td>
                                <td>{{$order->price}}</td>
                                <td>{{$order->created_at}}</td>

                                <td></td>

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

        function sepet_adet_guncelle (sepet_id) {
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

        function sepet_lisans_ay_guncelle (sepet_id) {

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

        function sepet_delete (sepet_id) {
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

        function sepet_delete_all () {
            $.ajax({
                type: 'post',
                url: '/acr/ftr/product/sepet/delete_all',
                success: function (veri) {
                    $('.sepet_count').html(0);
                    $('.sepet_row').fadeOut(400);
                }
            });
        }

        function order_active (id) {
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

        function fatura_active (id) {
            var order_id = id;
            $.ajax({
                type: 'post',
                url: '/acr/ftr/order/fatura/active',
                data: 'order_id=' + order_id,
                success: function () {
                }
            });
        }

    </script>
@stop