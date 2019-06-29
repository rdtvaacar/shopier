@extends('acr_ftr.index')
@section('header')
    <link rel="stylesheet" href="/css/acr_ftr/sepet.css">
    <link rel="stylesheet" href="/plugins/iCheck/all.css">
@stop
@section('acr_ftr')
    <section class="content">
        <div class="row">
            <div class=" col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border"><?php echo $sepet_nav ?></div>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class=""><a href="#havale_eft" data-toggle="tab" aria-expanded="true">Havale/EFT</a></li>
                            <li class="active"><a href="#kredi_karti" data-toggle="tab" aria-expanded="false">Kredi Kartı</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane " id="havale_eft">
                                <form method="post" action="/acr/ftr/card/payment/havale_eft">
                                    <?php
                                    echo csrf_field();
                                    foreach ($banks as $bank) {

                                    ?>
                                    <div style="width: 100%; cursor:pointer;" class="box-header with-border">
                                        <label style="width:80%; cursor:pointer;">
                                            <div style="float: left; " class="borderTd">
                                                <input required type="radio" name="bank_id" value="<?php echo $bank->id ?>" class="flat-red" style="position: absolute; opacity: 0;"></div>
                                            <div style="float: left; width: 90%; margin-left: 20px;">
                                                <div style="font-size: 14pt; float: left; width: 80%; "><?php echo $bank->name ?> - <span
                                                            style="font-weight: 200;"><?php echo $bank->bank_name . '/' . $bank->user_name ?></span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <?php }?>
                                    <div style="clear:both;"></div>
                                    <br>
                                    <?php echo $order_input ?>
                                    <button class="btn btn-lg btn-warning">ÖDEMEYİ HAVALE/EFT İLE TAMAMLA <span class="fa fa-angle-double-right"></span></button>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                            <div style="text-align: center;" class="tab-pane active" id="kredi_karti">
                                <a href="/acr/ftr/card/payment/bank_card<?php echo $order_link?>"><img src="/img/simdiAl.png"/> </a>
                            </div>
                            <!-- /.tab-pane -->


                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>

                </div>

            </div>
        </div>
    </section>
    <div style="clear:both;"></div>
    <div id="myModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img src="/icon/close48.png"/> </span></button>
                    <h4 class="modal-title">Yeni Adres Ekle</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">KAPAT</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@stop
@section('footer')
    <script src="/plugins/iCheck/icheck.min.js"></script>
    <script>
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass   : 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass   : 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass   : 'iradio_flat-green'
        });

        $('#city').change(function () {
            city_id = $(this).val();
            county_get(city_id);
        });
        function county_get(city_id) {
            $.ajax({
                type   : 'post',
                url    : '/acr/ftr/card/adress/county',
                data   : 'city_id=' + city_id,
                success: function (veri) {
                    $('#county').html(veri);
                }
            });
        }
        $('.type_b').on('ifChecked', function (event) {
            $('#kurumsal').hide();
        });
        $('.type_k').on('ifChecked', function (event) {
            $('#kurumsal').show();
        });
    </script>
@stop