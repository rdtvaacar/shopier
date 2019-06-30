@extends('acr_shopier.index')
@section('acr_shopier')
    <section class="content">
        <div class="row">
            {!! $msg !!}
            <div class=" col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">BİLGİLENDİRME</div>
                    <div class="box-body">
                        Promosyon kodları, sizin yada arkadaşlarınızın kullanabileceği şekilde tasarlanmıştır. Arkadaşlarınızın kullanabilmesi için kodu kopyalayıp arkadaşınıza göndermeniz gerekir.
                    </div>
                </div>
            </div>
            <div class=" col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">SİSTEM PORMOSYONLARI</div>
                    <div class="box-body">
                        @if(count($prvs)<1)
                            <div class="alert alert-warning">Herhangi bir ürün promosyonu bulunmuyor.</div>
                        @else
                            <table class="table table-hover">
                                <tr>
                                    <th>P.Kodu</th>
                                    <th>Ürün</th>
                                    <th>Son Geçerlilik Tarihi</th>
                                    <th>Oluşturma Tarihi</th>
                                </tr>
                                @foreach ($prvs as $pr)
                                    @if($pr->type ==2)
                                        <tr>
                                            <td>{{$pr->code}}</td>
                                            <td>{{$pr->pr_products}}</td>
                                            <td>{{date('d/m/Y',strtotime($pr->last_date))}}</td>
                                            <td>{{date('d/m/Y',strtotime($pr->created_at))}}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{$pr->code}}</td>
                                            <td>{{$pr->product->product_name}}</td>
                                            <td>{{date('d/m/Y',strtotime($pr->last_date))}}</td>
                                            <td>{{date('d/m/Y',strtotime($pr->created_at))}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        @endif
                    </div>
                </div>
            </div>
            <div class=" col-md-4">
                <div class="box box-success">
                    <div class="box-header with-border">PROMOSYON KODU KULLAN</div>
                    <div class="box-body">
                        <form method="post" action="/acr/ftr/promotion/code/active">
                            {{csrf_field()}}
                            <label>Pormosyon Kodunuz</label>
                            <input name="code" value="{{@$code}}" class="form-control" style="font-size: large; padding: 10px;"/>
                            <button type="submit" class="btn btn-primary btn-block">PROMOSYON KODUNU AKTİF ET</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class=" col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">ALIŞ VERİŞ PROMOSYONLARINIZ</div>
                    <div class="box-body">
                        @if(count($prs)<1)
                            <div class="alert alert-warning">Henüz alışveriş promosyonunuz bulunmuyor.</div>
                        @else
                            <table class="table table-hover">
                                <tr>
                                    <th>Ürün</th>
                                    <th>Pormosyon Kodu</th>
                                    <th>Satın Al</th>
                                    <th>Oluşturma Tarihi</th>
                                    <th>Durumu</th>
                                </tr>
                                @foreach ($prs as $pr)
                                    <tr>
                                        <td>{{@$pr->ps->product->product_name}}</td>
                                        <td>{{$pr->code}}</td>
                                        <td></td>
                                        <td>{{@$pr->created_at}}</td>
                                        <td>{!! @$pr->active==1?'<span class="text-success">AKTİF</span>':'<span class="text-danger">KULLANILDI</span>' !!}</td>
                                    </tr>
                                    @if(!empty($pr->promotion->pr_products))
                                        @foreach ($pr->promotion->pr_products as $product)
                                            <tr>
                                                <td>{{@$product->product->product_name}}</td>
                                                <td></td>
                                                <td><a href="/acr/ftr/product/sepet/ekle?product_id={{@$product->product->id}}&min_ay={{@$pr->promotion->min_ay}}&promotion_user_id={{@$pr->promotion->id}}&min_adet={{@$pr->promotion->min_adet
                                            }}">Satın Al</a></td>
                                                <td>{{@$pr->created_at}}</td>
                                                <td>{!! @$pr->active==1?'<span class="text-success">AKTİF</span>':'<span class="text-danger">KULLANILDI</span>' !!}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach

                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </section>
@stop
