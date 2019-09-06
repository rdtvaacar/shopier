@extends('acr_shopier.index')
@section('header')
    <style>
        .urun_sec {
            float: left;
            width: 24px;
            height: 24px;
        }

        .urun_detay {
            display: none;
        }

        .urun_baslik {
            padding: 3px 0 0 10px;
            margin: 0 0 0 0;
        }

        .urunler {
            position: relative;
        }
    </style>
@stop
@section('acr_shopier')
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 class="text-red" style="text-align: center;">TOPLAM FİYAT</h1>
            </div>
            <div class="box-body" style="font-size:64px; text-align: center;">
                <del><span class="text-red" style="font-size: 18pt;" id="price"></span></del>
                <span style="font-size: 18pt;">₺</span>
                <span style="font-size: 12pt;" class="text-success" id="dis_rate">0</span>
                <br>
                <span class="text-green" id="fiyat">0</span><span class="text-green">₺</span>
            </div>
            <div class="box-footer">
                <div class="col-md-6">
                    <select class="form-control" onchange="fiyat()" id="adet">
                        @for($i=1;$i<25; $i++)
                            <option value="{{$i}}">{{$i}} Kişi</option>
                        @endfor
                    </select>
                    <div style="text-align: center"><span style="display: none" id="kisi_basi" class="text-red"></span></div>
                </div>
                <div class="col-md-6">
                    <select class="form-control" onchange="fiyat()" id="ay">
                        @for($i=1;$i<25; $i++)
                            <option {{$i ==10?'selected':''}} value="{{$i}}">{{$i}} Aylık Abonelik</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="box-footer">
                <div id="sepete_ekle" onclick="sepete_ekle()" class="btn btn-warning btn-lg btn-block">SEPETE EKLE</div>
                <div id="sepete_ekle_loading" class="btn btn-warning btn-lg btn-block"><img src="/icon/load1.gif"/></div>
            </div>
        </div>
        <div class="alert alert-danger" style="text-align: justify;">
            Planlarımız bireysel kullanımlar içindir, eğer bir kurum iseniz yönetici aboneliği
            alabilirsiniz daha uygun olacaktır, yönetici aboneliğini personel sayısı kadar almalısınız,
            lütfen bizleri
            zor durumda
            bırakmayın, emeğe saygılı olunması gerektiğini
            düşünüyoruz. Aksi takdirde kul hakkıyla diğer tarafta alacaklı olacağımızı bilmenizi isteriz
            :) Empati sahibi olduğunuzu biliyor ve anlayışınız için teşekkür ediyoruz. Ayrıca yazılımsal araçlarla sistem kullanımı takip edilmekte ve kayıtlı olamayan bilgisayarlardan anormal erişimler sağlanması
            durumunda hukiki işlem başlatılmaktadır.
        </div>
    </div>
    <div class="col-md-9">
        @foreach ($product_ids as $product)
            <div class="box box-primary urunler">
                <div class="box-header with-border">
                    <input @if($product==1282)  readonly="readonly" disabled @endif onchange="sepet({{$product}})" class="urun_sec" {{in_array($product,[1282,1284,1285,1286,1287])?'checked':''}}   type="checkbox" id="urun_{{$product}}"
                           value="{{$product}}">
                    <h3 class="text-red urun_baslik" style=" float: left;"><label for="urun_{{$product}}">{{$urun[$product]->product_name}}</label>
                        @if($product==1282)<span class="text-orange">(Abonelik İşlemleri için zorunludur.)</span>@endif</h3>
                    <div class="btn-info btn-sm" onclick="urun_detay({{$product}})" style="position: absolute; right: 0px; top: -15px; cursor:pointer; ">DETAYLI İNCELE</div>
                    @if($product ==10)
                        <input id="usb_note" class="form-control" placeholder="Orinal MEB Etk Planları yada Uyarlanmış MEB Etk Planları ">
                    @endif
                </div>
                <div class="urun_detay urun_detay_{{$product}}">
                    <div class="box-body">
                        @if($product ==1282)
                            <div>
                                <div class="col-md-3">
                                    <h4>DİNAMİK EVRAKLAR</h4>
                                    <ul class="treeview-menu menu-open" style="display: block;">
                                        <li class="">
                                            <a href="/evrak/ogr">
                                                <i class="fa  fa-street-view"></i>
                                                Rapor/Evrak Listesi</a>
                                        </li>
                                        <li class="">
                                            <a href="/ogr/rapor/gr/yeni">
                                                <i class="glyphicon glyphicon-duplicate"></i>
                                                Gelişim Raporu Hazır Taslaklar</a>
                                        </li>
                                        <li class="">
                                            <a href="/ogr/gozellik">
                                                <i class="glyphicon glyphicon-education"></i>
                                                Gelişim Raporu - Özellik Seçim</a>
                                        </li>
                                        <li class="">
                                            <a href="/ogr/hizliGelisim">
                                                <i class="fa fa-forward"></i>
                                                Hızlı Gelişim Raporu - Tüm Sınıf</a>
                                        </li>
                                        <li class="">
                                            <a href="/ogr/gr/e_okul">
                                                <i class="glyphicon glyphicon-share-alt"></i>
                                                Gelişim Raporları E-Okul</a>
                                        </li>
                                        <li class="">
                                            <a href="/ogr/g_rapor_copy">
                                                <i class="fa fa-copy"></i>
                                                Gelişim Raporu Düz Metin</a>
                                        </li>
                                        <li class="">
                                            <a href="/ogr/tumGelisimYaz">
                                                <i class="glyphicon glyphicon-list-alt"></i>
                                                Tüm Gelişim Raporlarını Yazdır</a>
                                        </li>
                                        <li class="">
                                            <a href="/ogr/rapor/gf/yeni">
                                                <i class="fa fa-pencil-square-o"></i>
                                                Gelişim Gözlem Formu Oluştur</a>
                                        </li>
                                        <li class="">
                                            <a href="/ogr/tumGozlemFormlari">
                                                <i class="glyphicon glyphicon-list-alt"></i>
                                                Tüm Gelişim Gözlem Formları</a>
                                        </li>
                                        <li class="">
                                            <a href="/taslaklarim">
                                                <i class="fa  fa-gg"></i>
                                                Taslaklarım</a>
                                        </li>
                                        <li class="">
                                            <a href="/toplanti_tutanaklari">
                                                <i class="fa  fa-comments-o"></i>
                                                Zümre ve Veli Tutanakları</a>
                                        </li>
                                        <li class="">
                                            <a href="/kahvalti/listesi">
                                                <i class="fa  fa-cutlery"></i>
                                                Kahvaltı Listesi</a>
                                        </li>
                                        <li class="">
                                            <a href="/diger_evrak">
                                                <i class="glyphicon glyphicon-folder-open"></i>
                                                Diğer Evrak İşlemleri</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <h4>AİLE ZİYARETİ VE KATILIMI PLANLAMASI</h4>
                                    <ul class="treeview-menu menu-open" style="display: block;">
                                        <li class="">
                                            <a href="/z_aile">
                                                <i class="fa  fa-file-text-o"></i>
                                                Alie Ziyareti Planla/Ekle</a>
                                        </li>
                                        <li class="">
                                            <a href="/z_aile/ziyaretler">
                                                <i class="fa fa-check"></i>
                                                Yapılan Ziyaretler</a>
                                        </li>
                                        <li class="">
                                            <a href="/z_aile/yapilmayan">
                                                <i class="fa fa-times"></i>
                                                Yapılamayan Ziyaretler</a>
                                        </li>
                                        <li class="">
                                            <a href="/z_aile/katilim">
                                                <i class="fa fa-users"></i>
                                                Aile Katılımı Planla/Ekle</a>
                                        </li>
                                        <li class="">
                                            <a href="/z_aile/katilimlar">
                                                <i class="fa  fa-check"></i>
                                                Gerçekleşen Aile Katılımları</a>
                                        </li>
                                        <li class="">
                                            <a href="/z_aile/katilim/yapilmayan">
                                                <i class="fa fa-times"></i>
                                                Yapılamayan Aile Katılımları</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <h4>VELİ PAYLAŞIM SİSTEMİ</h4>
                                    <ul class="treeview-menu menu-open" style="display: block;">
                                        <li class="">
                                            <a href="/veli/mufredat">
                                                <i class="fa  fa-edit (alias)"></i>
                                                Günlük Müfredat</a>
                                        </li>
                                        <li class="">
                                            <a href="/veli/ogr/yemek/durum">
                                                <i class="fa fa-cutlery"></i>
                                                Yemek Yeme Durumları</a>
                                        </li>
                                        <li class="">
                                            <a href="/veli/ogr/uyku/durum">
                                                <i class="fa  fa-bed"></i>
                                                Günlük Uyku Durumları</a>
                                        </li>
                                        <li class="">
                                            <a href="/veli/ogr/devam/listesi">
                                                <i class="fa fa-list-alt"></i>
                                                Öğrenci Devam List</a>
                                        </li>
                                        <li class="">
                                            <a href="/veli/servisler">
                                                <i class="fa  fa-bus"></i>
                                                Servisler</a>
                                        </li>
                                        <li class="">
                                            <a href="/veli/ogr/mesaj">
                                                <i class="fa  fa-comments-o"></i>
                                                Veli Mesaj Ekranı</a>
                                        </li>
                                        <li class="">
                                            <a href="/ogrenci/foto/video/ekle/veli">
                                                <i class="fa fa-image (alias)"></i>
                                                Fotoğraf ve Video Paylaşımı</a>
                                        </li>
                                        <li class="">
                                            <a href="/veli/ogr/ilaclar">
                                                <i class="fa fa-ambulance"></i>
                                                Öğrenci İlaç Takibi</a>
                                        </li>
                                        <li class="">
                                            <a href="/ogr/veli/tanimla/veli/">
                                                <i class="fa  fa-child"></i>
                                                Öğrenci Veli Bilgileri</a>
                                        </li>
                                        <li class="">
                                            <a href="/fog">
                                                <i class="fa  fa-share"></i>
                                                Öğrenci Paylaşım Ekranı</a>
                                        </li>
                                        <li class="">
                                            <a href="/veli/ogr/oturum/bilgileri">
                                                <i class="fa  fa-expeditedssl"></i>
                                                Veli Giriş Şifreleri</a>
                                        </li>
                                        <li class="">
                                            <a href="/veli/ayar">
                                                <i class="fa  fa-gears (alias)"></i>
                                                Veli Paylaşım Ayarları</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-3">
                                    <h4>MUHASEBE SİSTEMİ</h4>
                                    <ul class="treeview-menu">
                                        <li class="">
                                            <a href="/yonetici/personeller">
                                                <i class=""></i>
                                                Personeller</a>
                                        </li>
                                        <li class="">
                                            <a href="/muhasebe/aidat">
                                                <i class=""></i>
                                                Aidat Sistemi</a>
                                        </li>
                                        <li class="">
                                            <a href="/muhasebe/ogrenci/aidat">
                                                <i class=""></i>
                                                Aidat Ücretlerini Düzenle</a>
                                        </li>
                                        <li class="">
                                            <a href="/muhasebe/ayarlar">
                                                <i class="fa fa-gears"></i>
                                                Muhasebe Ayarlar</a>
                                        </li>
                                        <li class="">
                                            <a href="/muhasebe/giderler">
                                                <i class=""></i>
                                                Giderler (Borç)</a>
                                        </li>
                                        <li class="">
                                            <a href="/muhasebe/gelirler">
                                                <i class=""></i>
                                                Gelirler (Alacak)</a>
                                        </li>
                                        <li class="">
                                            <a href="/muhasebe/mizan">
                                                <i class=""></i>
                                                Mizan (Hesap Dökümü)</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                        @if($product ==1283) <!-- Meraklı panda-->
                        36 - 48 Ay gelişim düzeyine göre hazırlanmış, eğlenceli ve değerler eğitimine önem verilmiş ve sınıf ortamı düşünülerek hazırlanmıştır.
                        @endif
                        @if($product ==1284)<!-- BP-->
                        48+  ay ve üstü  çocukların gelişim düzeyine göre hazırlanmış, eğlenceli ve değerler eğitimine önem verilmiş ve sınıf ortamı düşünülerek hazırlanmıştır. Tatlı tombik planlarından daha zengin bir içeriğe sahip ve
                        daha
                        yoğundur.
                        @endif
                        @if($product ==1285)<!-- TT-->
                        48+ ay ve üstü  çocukların gelişim düzeyine göre hazırlanmış, eğlenceli ve değerler eğitimine önem verilmiştir ve sınıf ortamı düşünülerek hazırlanmıştır.

                        @endif
                        @if($product ==1286)<!-- EF-->
                        Bu planımızda sıradışı etkinlik planlarına yer vermeye çalıştık, eğlenceli ve hayal gücümüzü geniş tutarak oyuna ağırlık verdik.
                        @endif
                        @if($product ==1287) <!-- MEB-->
                        Bilindiği üzere Milli Eğitim Bakanlığı, sınıf içi uygulamalarında öğretmene zengin örnekler sunmak ve doğru olmayan uygulamaların önüne geçilmesi amacıyla etkinlik havuzu hazırlamıştır. Öğretmenlerden Bu etkinlik
                        havuzunu kullanılarak günlük ve aylık planların oluşturması istenmektedir.
                        Bizde siz değerli öğretmenlerimizin evrak yükünü azaltarak, Milli Eğitim Bakanlığının Okul Öncesine yönelik 54 - 69 Ay gelişim düzeyine uygun hazırladığı etkinliklerden günlük ve aylık planlar oluşturduk,
                        interaktif içerikler (videolar, müzikler, hikayeler, slaytlar, sanat etkinlikleri vb) ekleyerek zenginleştirdik.
                        <br>
                        Toplamda 2 adet planımız bulunmaktadır.
                        <ul>
                            <li>Orjinal MEB etkinlikleriyle oluşturulan plan.</li>
                            <li>Uyarlanmış MEB etkinlikleriyle oluşturulan plan.</li>
                        </ul>

                        @endif
                        @if($product ==10)

                            <ol>
                                <li>Flash disk ile bir tane planın etkinlik dosyalarıyla beraber yükleyip adresinize ücretsiz kargo ile gönderiyoruz.</li>
                                <li>Her bir planın dosyaları etkinlikleriyle beraber 25GB civarındadır, bu yüzden yalnızca bir
                                    planı size gönderebiliriz.
                                </li>
                                <li>32GB USB 3.0 Özelliğindedir ve sizin olur.</li>
                                <li>Site aboneliği ile birlikte alınabilir.</li>
                                <li>Flash satışları kar amacıyla değil tamamen hizmet vermek amacıyla yapılmaktadır.</li>
                                <li>İnternet bağlantınız iyiyse ve sitemizden dosyaları indirebilecekseniz lütfen satın almayınız.</li>
                            </ol>
                        @endif
                    </div>
                </div>
                <div class="urun_detay urun_detay_{{$product}}">
                    <div class="box-footer ">
                        @if(in_array($product, [1283,1284,1285,1286,1287]))
                            <div>Eylül Ayı planlarını incelemek için <a href="/plan/tanitim">tıklayınız.</a></div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h1 class="text-red" style="text-align: center;">DİĞER ÜRÜNLERİMİZ</h1>
            </div>
            <div class="box-body" style="font-size:64px; text-align: center;">
                <?php
                $dir = opendir(base_path() . "/public_html/ogretmen_onlugu/"); //Burada Hangi Klasörün içersini listelemek istiyorsak onu seçtik
                while (($dosya = readdir($dir)) !== false) // While Döngüsüne girerek dosyamızı okuyoruz.
                {
                    if (!is_dir($dosya)) {
                        $files[] = $dosya;
                        // Bu if döngüsü dosya harici olan yani klasör yollarını gizlememizi sağlıyor. eyer if döngüsünü silerseniz klasör yolunu noktalarla gösterecektir..
                    }
                }
                closedir($dir); //İşimiz Bitti
                asort($files);
                $rand_onluk_key = rand(1, count($files) - 1)
                ?>
                <div class="col-md-4">
                    <div style="text-align: center; font-size: 18pt; font-weight: 600;">BİR ÖNLÜK İSTER MİSİN ?</div>
                    <a href="/ogretmen/onlugu">
                        <img src="/ogretmen_onlugu/{{$files[$rand_onluk_key]}}" style="float: left" width="100%" class="img-thumbnail"/>
                    </a>
                    <div style="clear:both;"></div>
                    <div style="font-size: 26pt;"><span class="fa fa-whatsapp"></span> <a href="tel:05428271936">0542 827 19 36</a></div>
                    <a class="btn btn-block btn-warning btn-lg" href="/ogretmen/onlugu">MODELLERİ İNCELE </a>
                </div>
                <div class="col-md-4">
                    <div style="text-align: center; font-size: 18pt; font-weight: 600;">OKUL SMS SİSTEMİ</div>
                    <a href="/acr/ftr/product/detail?product_id=15">
                        <img src="https://eticaret.webuldum.com/acr_files/18/medium/28047846769.png" style="float: left" width="100%" class="img-thumbnail"/>
                    </a>
                    <div style="clear:both;"></div>
                    <a class="btn btn-block btn-warning btn-lg" href="/acr/ftr/product/detail?product_id=15">ÜRÜNÜ İNCELE </a>
                </div>
                <div class="col-md-4">
                    <div style="text-align: center; font-size: 18pt; font-weight: 600;">KURUMSAL WEB SİTE PAKETİ</div>
                    <a href="/acr/ftr/product/detail?product_id=1281">
                        <img src="https://eticaret.webuldum.com/acr_files/1330/medium/kresinetnet-reklam.png" style="float: left" width="100%" class="img-thumbnail"/>
                    </a>
                    <div style="clear:both;"></div>
                    <a class="btn btn-block btn-warning btn-lg" href="/acr/ftr/product/detail?product_id=1281">ÜRÜNÜ İNCELE </a>
                </div>
            </div>
        </div>
    </div>
@stop
@section('footer')
    <script>
        function urun_detay(product_id) {
            $('.urun_detay_' + product_id).toggle()
        }

        function sepet(product_id) {
            if (product_id == 1282) {
                if ($('#urun_' + product_id).is(':checked') == false) {
                    $('.urun_sec').attr('disabled', 'disabled');
                    $('.urun_sec').attr('checked', false);
                    $('#urun_1282').removeAttr('disabled');
                    $('#fiyat').html(0)
                } else {
                    $('.urun_sec').removeAttr('disabled');
                    fiyat()
                }
            } else {
                fiyat()
            }
        }

        $(document).ready(function () {
            fiyat()
        });

        function fiyat() {
            var product_ids = '';
            $(".urunler :input").each(function () {
                if ($(this).is(':checked')) {
                    product_ids += $(this).val() + '_'; // This is the jquery object of the input, do what you will
                }
            });
            var adet = $('#adet').val();
            var ay = $('#ay').val();
            if (ay < 10) {
                if ($('#urun_10').is(':checked')) {
                    alert('USB Flash Disk İçin 10 Aylık Site Aboneliği Gerekir.')
                }
                $('#urun_10').prop('checked', false)
            }
            $.ajax({
                type: 'post',
                url: '/acr/ftr/lisans/urun/fiyat/hesapla',
                data: 'product_ids=' + product_ids + '&adet=' + adet + '&ay=' + ay,
                beforeSend: function () {
                    $('#sepete_ekle').hide();
                    $('#sepete_ekle_loading').show()
                },
                success: function (msg) {
                    $('#fiyat').html(msg[0]);
                    $('#dis_rate').html(msg[2]);
                    $('#price').html(msg[1]);
                    if (adet > 1) {
                        $('#kisi_basi').html('Kişi Başına: ' + msg[3]).show();
                    } else {
                        $('#kisi_basi').hide();
                    }
                    $('#sepete_ekle').show();
                    $('#sepete_ekle_loading').hide()
                }
            });
        }

        function sepete_ekle() {
            var usb_note = $('#usb_note').val();
            var usb = $('#urun_10').prop("checked");
            if ($('#urun_10').is(':checked')) {
                if (usb_note == '') {
                    alert('Gönderilmesini istediğiniz planı yazmalısınız!');
                    $('#usb_note').addClass('alert-warning').addClass('text-white');
                    return 0;
                }
            }
            if (usb_note != '' && usb == false) {
                if (confirm('USB Flash Disk - Plan Gönderimi Ürününü işaretlemediğiniz için planınız adresinize gönderilmeyecektir.') == false) {
                    return 0;
                }
            }
            var product_ids = '';
            var len = 0;
            $(".urunler :input").each(function () {
                if ($(this).is(':checked')) {
                    product_ids += $(this).val() + '_'; // This is the jquery object of the input, do what you will
                    len++
                }
            });
            if ($('#urun_10').is(':checked')) {
                if (len < 3) {
                    alert('Flash alımlarında en az bir plan aboneliği seçmelisiniz.');
                    return 0;
                }
            }
            var adet = $('#adet').val();
            var ay = $('#ay').val();
            $.ajax({
                type: 'post',
                url: '/acr/ftr/lisans/urun/sepete/ekle',
                data: 'product_ids=' + product_ids + '&adet=' + adet + '&ay=' + ay + '&usb_note=' + usb_note,
                success: function (msg) {
                    window.location.href = "https://okuloncesievrak.com/acr/ftr/card/sepet";
                }
            });
        }
    </script>
@stop