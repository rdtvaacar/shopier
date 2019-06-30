@extends('acr_shopier.index')
@section('header')
    <link rel="stylesheet" href="/css/acr_shopier/sepet.css">
    <link rel="stylesheet" href="/plugins/iCheck/all.css">
@stop
@section('acr_shopier')
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">Şirket Bilgileri</div>
                    <div class="box-body">
                        <form method="post" action="/acr/ftr/config/company/conf/update">
                            <?php echo csrf_field() ?>
                            <div class="form-group with-border">
                                <label>Şirket İsmi</label>
                                <input class="form-control" name="name" value="{{@$company_conf->name}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Şehir</label>
                                <input class="form-control" name="city" value="{{@$company_conf->city}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>İlçe</label>
                                <input class="form-control" name="county" value="{{@$company_conf->county}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Adres</label>
                                <textarea class="form-control" type="adress"
                                          name="adress">{{@$company_conf->adress}}</textarea>
                            </div>
                            <div class="form-group with-border">
                                <label>Email</label>
                                <input class="form-control" type="email" name="email"
                                       value="{{@$company_conf->email}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Şirket URL</label>
                                <input class="form-control" type="url" name="url" value="{{@$company_conf->url}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Telefon</label>
                                <input class="form-control" type="text" name="tel" value="{{@$company_conf->tel}}"/>
                            </div>
                            <input name="id" type="hidden" value="{{@$company_conf->id}}"/>
                            <button class="btn btn-primary"> KAYDET</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">BANKA BİLGİLERİ
                        <button style="float: right;" data-toggle="modal" data-target="#myModal"
                                class="btn btn-success">YENİ BANKA EKLE
                        </button>
                    </div>
                    <div class="box-body">
                        <?php
                        echo csrf_field();
                        foreach ($banks as $bank) {
                        $checked = @$bank->active == 1 ? 'checked' : '';
                        ?>
                        <div id="bank_div_{{$bank->id}}" style="width: 100%; cursor:pointer;"
                             class="box-header with-border">
                            <label style="width:80%; cursor:pointer;">
                                <div style="float: left; " class="borderTd">
                                    <input type="checkbox" name="bank_id" name="bank_id" value="<?php echo $bank->id ?>"
                                           class="flat-red"
                                           <?php echo $checked ?> style="position: absolute; opacity: 0;"></div>
                                <div style="float: left; width: 90%; margin-left: 20px;">
                                    <div style="font-size: 14pt; float: left; width: 80%; "><?php echo $bank->name ?> -
                                        <span style="font-weight: 200;"><?php echo $bank->bank_name . '/' . $bank->user_name ?></span>
                                    </div>
                                </div>
                            </label>
                            <div style="font-size: 16pt; float: right; width: 15%; ">
                                <span style="margin-left: 30px; cursor:pointer;"
                                      onclick="bank_edit(<?php echo $bank->id ?>)" class="fa fa-edit"></span>
                                <span style="margin-left: 30px; cursor:pointer;"
                                      onclick="bank_delete(<?php echo $bank->id ?>)" class="fa fa-trash"></span>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">KREDİ KARTI BİLGİLERİ İYZİCO</div>
                    <div class="box-body">
                        <form method="post" action="/acr/ftr/config/iyzico/update">
                            <?php echo csrf_field() ?>
                            <div class="form-group with-border">
                                <label>SetApiKey</label>
                                <input class="form-control" name="setApiKey" value="{{@$iyzico->setApiKey}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>SetSecretKey</label>
                                <input class="form-control" name="setSecretKey" value="{{@$iyzico->setSecretKey}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>SetBaseUrl</label>
                                <input class="form-control" name="setBaseUrl" value="{{@$iyzico->setBaseUrl}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>SetCallbackUrl</label>
                                <input class="form-control" placeholder="http://example.com/odeme_sonuc"
                                       name="setCallbackUrl" value="{{@$iyzico->setCallbackUrl}}"/>
                            </div>
                            <input name="id" type="hidden" value="{{@$iyzico->id}}"/>
                            <button class="btn btn-primary"> KAYDET</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">Database Tablo Ayarları</div>
                    <div class="box-body">
                        <form method="post" action="/acr/ftr/config/user_table_update">
                            <?php echo csrf_field() ?>
                            <div class="form-group with-border">
                                <label>NAME</label>
                                <input class="form-control" name="name" value="{{@$user_table->name}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>User_name</label>
                                <input class="form-control" name="user_name" value="{{@$user_table->user_name}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Email</label>
                                <input class="form-control" name="email" value="{{@$user_table->email}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Lisans Durum</label>
                                <input class="form-control" name="lisans_durum" value="{{@$user_table->lisans_durum}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Lisans Başlangıç</label>
                                <input class="form-control" name="lisans_baslangic"
                                       value="{{@$user_table->lisans_baslangic}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Lisans Bitiş</label>
                                <input class="form-control" name="lisans_bitis" value="{{@$user_table->lisans_bitis}}"/>
                            </div>
                            <input name="id" type="hidden" value="{{@$user_table->id}}"/>
                            <button class="btn btn-primary"> KAYDET</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">Paraşüt Fatura Bilgileri</div>
                    <div class="box-body">
                        <form method="post" action="/acr/ftr/config/parasut/conf/update">
                            <?php echo csrf_field() ?>
                            <div class="form-group with-border">
                                <label>Client ID</label>
                                <input class="form-control" name="client_id" value="{{@$parasut_conf->client_id}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Client Secret</label>
                                <input class="form-control" name="client_secret"
                                       value="{{@$parasut_conf->client_secret}}"/>
                            </div>
                            <div class=" form-group with-border">
                                <label>Üyelik Numarsı (Company ID)</label>
                                <input class="form-control" type="number" name="company_id"
                                       value="{{@$parasut_conf->company_id}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Email</label>
                                <input class="form-control" type="email" name="username"
                                       value="{{@$parasut_conf->username}}"/>
                            </div>
                            <div class="form-group with-border">
                                <label>Şifre</label>
                                <input class="form-control" type="password" name="password"
                                       value="{{@$parasut_conf->password}}"/>
                            </div>
                            <input name="id" type="hidden" value="{{@$parasut_conf->id}}"/>
                            <button class="btn btn-primary"> KAYDET</button>
                        </form>
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
                    <h4 class="modal-title">Yeni Banka Ekle</h4>
                </div>
                <div class="modal-body">
                    <div id="bank_form_div"><?php echo $bank_form ?></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">KAPAT</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
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

        function bank_edit(bank_id) {
            $.ajax({
                type: 'post',
                url: '/acr/ftr/bank/edit',
                data: 'bank_id=' + bank_id,
                success: function (veri) {
                    $('#myModal').modal('show');
                    $('#bank_form_div').html(veri);
                }
            });
        }

        function bank_delete(bank_id) {
            if (confirm('Banka bilgilerini silmek istediğinizden eminmisiniz.') == true) {
                $.ajax({
                    type: 'post',
                    url: '/acr/ftr/bank/delete',
                    data: 'bank_id=' + bank_id,
                    success: function () {
                        $('#bank_div_' + bank_id).fadeOut(400);
                    }
                });
            }
        }

        $('input').on('ifChecked', function (event) {
            var bank_id = $(this).val();
            $.ajax({
                type: 'post',
                url: '/acr/ftr/bank/active',
                data: 'bank_id=' + bank_id,
                success: function () {

                }
            });

        });
        $('input').on('ifUnchecked', function (event) {
            var bank_id = $(this).val();
            $.ajax({
                type: 'post',
                url: '/acr/ftr/bank/deactive',
                data: 'bank_id=' + bank_id,
                success: function () {

                }
            });

        });
    </script>
@stop