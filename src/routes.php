<?php
Route::group(['middleware' => ['web']], function () {
    Route::group([
        'namespace' => 'Acr\Shopier\Controllers',
        'prefix'    => 'acr/ftr'
    ], function () {
        Route::get('/lisans/urunleri', 'AcrSepetController@lisans_urunleri');
        Route::post('/lisans/urun/fiyat/hesapla', 'AcrSepetController@lisans_urun_fiyat_hesapla');
        Route::post('/lisans/urun/sepete/ekle', 'AcrSepetController@lisans_urun_sepete_ekle');
        Route::get('/', 'AcrShopierController@index');
        Route::post('/product/ara/', 'AcrShopierController@product_search');
        Route::get('/product/detail', 'AcrShopierController@product_detail');
        Route::get('/product', 'AcrShopierController@my_product');
        Route::post('/product', 'AcrShopierController@my_product');
        Route::post('/product/attribute/modal', 'AcrShopierController@attribute_modal');
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
        Route::post('/product/image/modal', 'AcrShopierController@image_modal');
        Route::post('/product/img', 'AcrShopierController@product_img');
        Route::post('/product/sepet/ekle', 'AcrSepetController@product_sepet_ekle');
        Route::get('/product/sepet/ekle', 'AcrSepetController@product_sepet_ekle');
        Route::post('/product/categories', 'AcrShopierController@categories');
        // paraşüt
        Route::get('/parasut', 'ParasutController@index');
        Route::get('/fit', 'FitBulutController@getUserLists'); // fit client akif bağlantı
        Route::get('/soap', 'AcrSoapController@show');
        Route::get('/acrFit', 'FitController@connect');

        Route::group(['middleware' => ['auth']], function () {
            Route::post('/order/active', 'AcrSepetController@orders_active');
            Route::post('/order/cancel', 'AcrSepetController@order_cancel');
            Route::post('/promotion/code/active', 'AcrSepetController@promotion_code_active');
            Route::get('/promotion', 'AcrShopierController@promotion');
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
            Route::post('/order/result', 'AcrSepetController@order_result');
            Route::get('/card/payment/bank_card', 'AcrSepetController@payment_bank_card');


            // orders
            Route::get('/orders', 'AcrSepetController@orders');
            /// admin
            Route::group(['middleware' => ['admin']], function () {
                Route::get('/product/new', 'AcrShopierController@new_product');
                Route::get('/admin/promotions', 'AcrShopierController@admin_promotions');
                Route::get('/admin/promotion/create', 'AcrShopierController@admin_promotion_create');
                Route::post('/admin/promotion/create', 'AcrShopierController@admin_promotion_create');
                Route::post('/admin/promotion/kod/delete', 'AcrShopierController@admin_promotion_kod_delete');
                Route::post('/admin/promotion/kod/refresh', 'AcrShopierController@promotion_kod_refresh');

                //Route::post('/product/search_row', 'AcrShopierController@product_search_row');
                Route::post('/product/add', 'AcrShopierController@add_product');
                Route::post('/product/delete', 'AcrShopierController@delete_product');
                Route::get('/config', 'AcrShopierController@config');
                Route::post('/config/company/conf/update', 'AcrShopierController@company_conf_update');
                Route::post('/bank/create', 'AcrShopierController@bank_create');
                Route::post('/bank/edit', 'AcrShopierController@bank_edit');
                Route::post('/bank/delete', 'AcrShopierController@bank_delete');
                Route::post('/bank/active', 'AcrShopierController@active_bank');
                Route::post('/bank/deactive', 'AcrShopierController@deactive_bank');
                Route::post('/config/user_table_update', 'AcrShopierController@user_table_update');
                Route::post('/config/parasut/conf/update', 'AcrShopierController@parasut_conf_update');
                Route::post('/config/iyzico/update', 'AcrShopierController@iyzico_update');
                Route::get('/admin/sales_invoices', 'AcrShopierController@sales_invoices');
                Route::delete('/admin/sales_invoices', 'ParasutController@sales_invoice_delete');
                Route::get('/admin/orders', 'AcrSepetController@admin_orders');
                Route::get('/admin/orders/cleaner', 'AcrSepetController@admin_orders_cleaner');
                Route::post('/order/active/admin', 'AcrSepetController@orders_active_admin');
                Route::post('/order/fatura/active', 'AcrSepetController@order_fatura_active');
                Route::post('/order/deactive', 'AcrSepetController@orders_deactive');
                Route::get('/admin/siparis/faturalar', 'AcrShopierController@admin_sales_incoices');
                Route::post('/admin/siparis/faturalar', 'AcrShopierController@admin_sales_incoices');
                Route::get('/admin/siparis/to/fatura', 'AcrSepetController@admin_sales_to_incoices');
                Route::get('/admin/fatura/yazdir', 'AcrShopierController@admin_fatura_yazdir');
                Route::get('/admin/e_arsive/create', 'AcrSepetController@admin_e_arsive_create');
                Route::get('/admin/e_arsive/basarili', function () {
                    return View::make('acr_shopier::basarili_fatura');
                });
            });
        });


    });
});