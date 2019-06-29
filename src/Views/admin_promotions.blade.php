@extends('acr_ftr.index')
@section('acr_ftr')
    <section class="content">
        <div class="row">
            {!! $msg !!}
            <div class=" col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">PROMOSYONLARINIZ</div>
                    <div class="box-body">
                        <div>Burada promosyon ürünlerinizi oluşburabilirsiniz, oluşturulan promosyon ürünleri promosyon kodu ile ve markete eklenmişse kullanılabilir. Doğrudan baplantıyla kullanılamazlar.</div>
                        <table class="table table-hover">
                            <tr>
                                <th>P.Kodu</th>
                                <th>Yenile</th>
                                <th>Ürün</th>
                                <th>Tip</th>
                                <th>Oluşturma Tarihi</th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach ($prs as $pr)
                                @if($pr->type==2)
                                    <tr id="code_tr_{{$pr->id}}">
                                        <td id="code_{{$pr->id}}">{{$pr->code}}</td>
                                        <td>
                                            <div onclick="kod_yenile({{$pr->id}})" class="btn btn-danger btn-sm">KOD YENİLE</div>
                                        </td>
                                        <td>
                                            @foreach ($pr->pr_products as $product)
                                                <span class="badge">{{@$product->product->product_name}}</span>
                                            @endforeach
                                        </td>
                                        <td>İndirim Tutarı</td>
                                        <td>{{$pr->created_at}}</td>
                                        <td><a class="btn btn-warning btn-sm" href="/acr/ftr/admin/promotions?id={{$pr->id}}">DÜZENLE</a></td>
                                        <td>
                                            <div class="btn btn-danger btn-sm" onclick="sil({{$pr->id}})">SİL</div>
                                        </td>
                                    </tr>
                                @else
                                    <tr id="code_tr_{{$pr->id}}">
                                        <td id="code_{{$pr->id}}">{{$pr->code}}</td>
                                        <td>
                                            <div onclick="kod_yenile({{$pr->id}})" class="btn btn-danger btn-sm">KOD YENİLE</div>
                                        </td>
                                        <td>{{$pr->product->product_name}}</td>
                                        <td>Sabit Ürün</td>
                                        <td>{{$pr->created_at}}</td>
                                        <td><a class="btn btn-warning btn-sm" href="/acr/ftr/admin/promotions?id={{$pr->id}}">DÜZENLE</a></td>
                                        <td>
                                            <div class="btn btn-danger btn-sm" onclick="sil({{$pr->id}})">SİL</div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class=" col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">PROMOSYON</div>
                    <div class="box-body">
                        <form method="post" action="/acr/ftr/admin/promotion/create">
                            {{csrf_field()}}
                            <label>Ürün ID (Virgülle Ayırınız)</label>
                            <input name="product_id" value="{{@$prd->product_id}}" class="form-control"/>
                            <label>Ürün Sayısı</label>
                            <input name="son" value="{{@$prd->son}}" class="form-control"/>
                            <label>İndirim Tutarı</label>
                            <input name="price" value="{{@$prd->price}}" class="form-control"/>
                            <label>Type</label>
                            <select class="form-control" name="type">
                                <option {{@$prd->type==1?'selected':''}} value="1">Sabit Ürün (Ürüne Yönlendirme)</option>
                                <option {{@$prd->type==2?'selected':''}} value="2">İndirim Tutarı</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-block">PROMOSYON OLUŞTUR</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('footer')
    <script>
        function kod_yenile (id) {
            if (confirm('Kodu güncellemek mevcut kodun geçirsiz olmasını sebep olur, emin misiniz?') == true) {
                $.ajax({
                    type: 'post',
                    url: '/acr/ftr/admin/promotion/kod/refresh',
                    data: 'id=' + id,
                    success: function (veri) {
                        $('#code_' + id).html(veri);
                    }
                });
            }
        }

        function sil (id) {
            if (confirm('Kodu güncellemek mevcut kodun geçirsiz olmasını sebep olur, emin misiniz?') == true) {
                $.ajax({
                    type: 'post',
                    url: '/acr/ftr/admin/promotion/kod/delete',
                    data: 'id=' + id,
                    success: function (veri) {
                        $('#code_tr_' + id).hide();
                    }
                });
            }
        }

    </script>
@stop