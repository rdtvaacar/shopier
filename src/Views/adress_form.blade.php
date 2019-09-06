<form method="post" action="/acr/ftr/card/adress/create">
    {{csrf_field()}}
    <div class="form-group">
        <label>Adres İsmi</label>
        <input required name="name" id="name" class="form-control" placeholder="Adres İsmi, Örn: Ev adresim, Kurum Adresim" value="{{@$adress->name}}">
    </div>
    <div class="form-group">
        <label>Alıcı İsmi (Ad Soyad şeklinde giriniz aksi halde sistem hata verir.)</label>
        <input required name="invoice_name" id="invoice_name" class="form-control" placeholder="Adınız Soyadınız" value="{{@$adress->invoice_name}}">
    </div>
    <div class="form-group">
        <label>T.C. Kimlik No (11 Hane olmalıdır.) </label>
        <input type="number" maxlength="11" size="11" required name="tc" id="tc" class="form-control" placeholder="Kimlik Numaranızı " value="{{@$adress->tc}}">
    </div>
    <div class="form-group">
        <label>Şehir</label>
        <select required name="city" id="city" class="form-control">
            <option value="">Şehir Seçiniz</option>
            @foreach ($cities as $city)
                @if ($city->id != 0) {
                <?php  $select = $city->id == @$adress->city_id ? 'selected="selected"' : '' ?>
                @else
                    <?php $select = '' ?>
                @endif
                <option {{$select}} value="{{$city->id}}">
                    {{$city->name}}
                </option>
            @endforeach
        </select>
    </div>
    <div id="county">
        @if (!empty($adress->city_id))
            {!!  $county_row !!}
        @endif
    </div>
    <div class="form-group">
        <label>Açık Adres</label>
        <textarea required name="adress" class="form-control" placeholder="Açık Adres">{{@$adress->adress}}</textarea>
    </div>
    <div class="form-group">
        <label>Adres Posta Kodu</label>
        <input required name="post_code" type="number" class="form-control" placeholder="Posta Kodu" value="{{@$adress->post_code}}">
    </div>
    <div class="form-group">
        <label>Telefon</label>
        <input required name="tel" type="number" class="form-control" placeholder="Telefon" value="{{@$adress->tel}}">
    </div>
    @if (@$adress->type == 1 || empty($adress->type))
        <?php $type_c_1 = 'checked';
        $type_c_2 = '' ?>
    @else
        <?php
        $type_c_1 = '';
        $type_c_2 = 'checked' ?>
    @endif
    <div class="form-group">
        <label class="type_b">
            <input type="radio" name="type" value="1" class="flat-red" {{$type_c_1}} style="position: absolute; opacity: 0;">
            <div style="margin-left: 10px; font-size: 14pt; float: right;">Bireysel</div>
        </label>
        <label style="margin-left: 30px;" class="type_k">
            <input type="radio" name="type" value="2" class="flat-red" {{$type_c_2}} style="position: absolute; opacity: 0;">
            <div style="margin-left: 10px; font-size: 14pt; float: right;">Kurumsal</div>
        </label>
    </div>
    <?php  $display = @$adress->type == 1 || empty(@$adress->type) ? 'none' : 'normal'  ?>
    <div id="kurumsal" style="display: {{$display}}">
        <div class="form-group">
            <label>Kurum İsmi</label>
            <input name="company" class="form-control" placeholder="Kurum İsmi" value="{{@$adress->company}}">
        </div>
        <div class="form-group">
            <label>Kurum Vergi No</label>
            <input name="tax_number" type="number" class="form-control" placeholder="Kurum Vergi No" value="{{@$adress->tax_number}}">
        </div>
        <div class="form-group">
            <label>Kurum Vergi Dairesi</label>
            <input name="tax_office" class="form-control" placeholder="Kurum Vergi Dairesi" value="{{@$adress->tax_office}}">
        </div>
        @if (@$adress->e_fatura == 2)
            <?php $e_fatura_check = 'checked' ?>
        @else
            <?php $e_fatura_check = '' ?>
        @endif
        <label for="e_fatura" class="">
            <input name="e_fatura" id="e_fatura" type="checkbox" {{$e_fatura_check}} class="minimal-red" value="2" style="position: absolute; opacity: 0;">
            <div style="margin-left: 10px; font-size: 14pt; float: right;">E-Fatura Mükellefiyim</div>
        </label>
    </div>
    <input type="hidden" name="adress_id" value="{{@$adress->id}}">
    <input type="hidden" name="user_id" value="{{@$user_id}}">
    <button type="submit" class="btn btn-primary">ADRES KAYDET <span class="fa fa-angle-double-right"></span></button>
</form>
<div style="clear:both;"></div>