@if(count($p_kats)>0)
    <select class="form-control" id="kat_{{$kat_div}}" style="float: left; width:280px;">
        <option value="">Seçiniz</option>
        @foreach($p_kats as $kat)
            <option value="{{$kat->id}}"><b>{{@$kat->kat_isim}}</b></option>
        @endforeach
    </select>
    <script>
        $('#kat_2').change(function () {
            var kat_id = $(this).val();
            categories(2, kat_id);
        });
        $('#kat_3').change(function () {
            var kat_id = $(this).val();
            categories(3, kat_id);
        })
    </script>
@endif