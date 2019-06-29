<?php
Route::group(['middleware' => ['web']], function () {
    Route::group([
        'namespace' => 'Acr\Ftr\Controllers',
        'prefix'    => 'acr/ftr'
    ], function () {
        Route::get('/lisans/urunleri', 'AcrSepetController@lisans_urunleri');
        Route::post('/lisans/urun/fiyat/hesapla', 'AcrSepetController@lisans_urun_fiyat_hesapla');
        Route::post('/lisans/urun/sepete/ekle', 'AcrSepetController@lisans_urun_sepete_ekle');
        Route::get('/', 'AcrFtrController@index');
        Route::post('/product/ara/', 'AcrFtrController@product_search');
        Route::get('/product/detail', 'AcrFtrController@product_detail');
        Route::get('/product', 'AcrFtrController@my_product');
        Route::post('/product', 'AcrFtrController@my_product');
        Route::post('/product/attribute/modal', 'AcrFtrController@attribute_modal');
        Route::post('/product/sepet/create', 'AcrSepetController@create');
        Route::post('/product/sepet/products', 'AcrSepetController@products');
        Route::post('/product/sepet/sepet_adet_guncelle', 'AcrSepetController@sepet_adet_guncelle');
        Route::post('/product/sepet/sepet_lisans_ay_guncelle', 'AcrSepetController@sepet_lisans_ay_guncelle');
        Route::post('/product/sepet/delete', 'AcrSepetController@delete');
        Route::post('/product/sepet/sepet_total_price', 'AcrSepetController@sepet_total_price');
        Route::post('/product/sepet/product_sepet_total_price', 'AcrSepetController@product_sepet_total_price');
        Route::post('/product/sepet/discount', 'AcrSepetController@discount');
        Route::post('/product/sepet/delete_all', 'AcrSepetController@delete_all');
        Route::get('/card/sepet', 'AcrSepetController@card');
        Route::post('/product/image/modal', 'AcrFtrController@image_modal');
        Route::post('/product/img', 'AcrFtrController@product_img');
        Route::post('/product/sepet/ekle', 'AcrSepetController@product_sepet_ekle');
        Route::get('/product/sepet/ekle', 'AcrSepetController@product_sepet_ekle');
        Route::post('/product/categories', 'AcrFtrController@categories');
        // paraşüt
        Route::get('/parasut', 'ParasutController@index');
        Route::get('/fit', 'FitBulutController@getUserLists'); // fit client akif bağlantı
        Route::get('/soap', 'AcrSoapController@show');
        Route::get('/acrFit', 'FitController@connect');

        Route::group(['middleware' => ['auth']], function () {
            Route::post('/order/active', 'AcrSepetController@orders_active');
            Route::post('/order/cancel', 'AcrSepetController@order_cancel');
            Route::post('/promotion/code/active', 'AcrSepetController@promotion_code_active');
            Route::get('/promotion', 'AcrFtrController@promotion');
            // adress
            Route::get('/card/adress', 'AcrSepetController@adress');
            Route::post('/card/adress/county', 'AcrSepetController@county_row');
            Route::post('/card/adress/create', 'AcrSepetController@adress_create');
            Route::post('/card/adress/edit', 'AcrSepetController@adress_edit');
            Route::get('/card/adress/edit', 'AcrSepetController@card_adress_edit');
            Route::post('/card/adress/delete', 'AcrSepetController@adress_delete');
            //payment
            Route::get('/card/payment', 'AcrSepetController@payment');
            Route::post('/card/payment', 'AcrSepetController@payment');
            Route::post('/card/payment/havale_eft', 'AcrSepetController@paymet_havale_eft');
            Route::get('/card/payment/havale_eft', 'AcrSepetController@paymet_havale_eft');
            Route::post('/order/result', 'iyzicoController@order_result');
            Route::get('/card/payment/bank_card', 'AcrSepetController@payment_bank_card');


            // orders
            Route::get('/orders', 'AcrSepetController@orders');
            /// admin
            Route::group(['middleware' => ['admin']], function () {
                Route::get('/product/new', 'AcrFtrController@new_product');
                Route::get('/admin/promotions', 'AcrFtrController@admin_promotions');
                Route::get('/admin/promotion/create', 'AcrFtrController@admin_promotion_create');
                Route::post('/admin/promotion/create', 'AcrFtrController@admin_promotion_create');
                Route::post('/admin/promotion/kod/delete', 'AcrFtrController@admin_promotion_kod_delete');
                Route::post('/admin/promotion/kod/refresh', 'AcrFtrController@promotion_kod_refresh');

                //Route::post('/product/search_row', 'AcrFtrController@product_search_row');
                Route::post('/product/add', 'AcrFtrController@add_product');
                Route::post('/product/delete', 'AcrFtrController@delete_product');
                Route::get('/config', 'AcrFtrController@config');
                Route::post('/config/company/conf/update', 'AcrFtrController@company_conf_update');
                Route::post('/bank/create', 'AcrFtrController@bank_create');
                Route::post('/bank/edit', 'AcrFtrController@bank_edit');
                Route::post('/bank/delete', 'AcrFtrController@bank_delete');
                Route::post('/bank/active', 'AcrFtrController@active_bank');
                Route::post('/bank/deactive', 'AcrFtrController@deactive_bank');
                Route::post('/config/user_table_update', 'AcrFtrController@user_table_update');
                Route::post('/config/parasut/conf/update', 'AcrFtrController@parasut_conf_update');
                Route::post('/config/iyzico/update', 'AcrFtrController@iyzico_update');
                Route::get('/admin/sales_invoices', 'AcrFtrController@sales_invoices');
                Route::delete('/admin/sales_invoices', 'ParasutController@sales_invoice_delete');
                Route::get('/admin/orders', 'AcrSepetController@admin_orders');
                Route::get('/admin/orders/cleaner', 'AcrSepetController@admin_orders_cleaner');
                Route::post('/order/active/admin', 'AcrSepetController@orders_active_admin');
                Route::post('/order/fatura/active', 'AcrSepetController@order_fatura_active');
                Route::post('/order/deactive', 'AcrSepetController@orders_deactive');
                Route::get('/admin/siparis/faturalar', 'AcrFtrController@admin_sales_incoices');
                Route::post('/admin/siparis/faturalar', 'AcrFtrController@admin_sales_incoices');
                Route::get('/admin/siparis/to/fatura', 'AcrSepetController@admin_sales_to_incoices');
                Route::get('/admin/fatura/yazdir', 'AcrFtrController@admin_fatura_yazdir');
                Route::get('/admin/e_arsive/create', 'AcrSepetController@admin_e_arsive_create');
                Route::get('/admin/e_arsive/basarili', function () {
                    return View::make('acr_shopier::basarili_fatura');
                });
            });
        });


    });
});