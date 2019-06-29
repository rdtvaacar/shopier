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
                    <div class="box-header with-border"><?php echo $sepet_nav ?>
                        <button style="float: right;" data-toggle="modal" data-target="#myModal"
                                class="btn btn-success">YENİ ADRES EKLE
                        </button>
                    </div>
                    <div class="box-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(count($adresses)==0)
                            <div style="text-align: center; width: 100%;">
                                <button data-toggle="modal" data-target="#myModal" class="btn btn-success btn-lg">YENİ
                                    ADRES EKLE
                                </button>
                            </div>
                        @else
                            <form method="post" action="/acr/ftr/card/payment">
                                <?php
                                echo csrf_field();
                                foreach ($adresses as $adress) {
                                $checked = @$adress->active == 1 ? 'checked' : '';
                                ?>
                                <div id="adres_div_{{$adress->id}}" class="box-header with-border">
                                    <label style="width: 80%;">
                                        <div style="float: left; " class="borderTd">
                                            <input type="radio" name="adress" id="adress"
                                                   value="<?php echo $adress->id ?>" class="flat-red"
                                                   <?php echo $checked ?> style="position: absolute; opacity: 0;"></div>
                                        <div style="float: left; width: 90%; margin-left: 20px;">
                                            <div style="font-size: 14pt; ">{{ $adress->name}}- <span
                                                        style="font-weight: 200;">{{$adress->county->name . '/' . $adress->city->name }}</span>
                                            </div>
                                        </div>
                                    </label>
                                    <div style="font-size: 16pt; float: right; width: 15%; ">
                                        <a style="margin-left: 30px; cursor:pointer;"
                                           href="/acr/ftr/card/adress/edit?adres_id=<?php echo $adress->id ?>"
                                           class="fa fa-edit"></a>
                                        <span style="margin-left: 30px; cursor:pointer;"
                                              onclick="adress_delete(<?php echo $adress->id ?>)"
                                              class="fa fa-trash"></span>
                                    </div>
                                </div>
                                <?php echo $order_input ?>
                                <?php }
                                if($adresses->count() > 0) {?>
                                <button type="submit" class="btn btn-lg btn-warning"> ÖDEME BİLGİLERİ <span
                                            class="fa fa-angle-double-right"></span></button>
                                <?php } ?>
                            </form>
                        @endif
                        <div style="clear: both;"></div>
                        <br>
                        <div style="font-size:10pt; text-align: center;" class="alert alert-info">
                            NOT: Vergi usul kanununa göre kişisel bilgileriniz vergilendirme için
                            kullanılacak olup kesinlikle 3. kişilerle paylaşılmayacaktır.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="myModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><img src="/icon/close48.png"/> </span></button>
                    <h4 class="modal-title">Yeni Adres Ekle</h4>
                </div>
                <div class="modal-body">
                    <div id="adres_form_div"><?php echo $adres_form ?></div>
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
            radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });

        $('#city').change(function () {
            city_id = $(this).val();
            county_get(city_id);
        });

        function county_get(city_id) {
            $.ajax({
                type: 'post',
                url: '/acr/ftr/card/adress/county',
                data: 'city_id=' + city_id,
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

        function adress_delete(adres_id) {
            if (confirm('Adres bilgilerini silmek istediğinizden eminmisiniz.') == true) {
                $.ajax({
                    type: 'post',
                    url: '/acr/ftr/card/adress/delete',
                    data: 'adres_id=' + adres_id,
                    success: function () {
                        $('#adres_div_' + adres_id).fadeOut(400);
                    }
                });
            }
        }


    </script>
@stop