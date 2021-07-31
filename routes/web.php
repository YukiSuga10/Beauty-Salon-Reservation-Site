<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//ユーザ側ルーティング
Auth::routes();

Route::get('/', 'HomeController@show_salon');
Route::get('/show_salon', 'HomeController@show_salon');
Route::get('/salon/{admin}','HomeController@show_salonPage');
Route::get('/salon/{admin}/info_stylist','HomeController@info_stylist');
Route::get('/salon/{admin}/reserve', 'ReserveController@reserve_date_stylist');
Route::get('/info_stylist', 'HomeController@info_stylist');

Route::get('/mypage','HomeController@mypage');
Route::get('/show_reserve/{date}', 'HomeController@show_reserve');
Route::get('/past_reserve', 'HomeController@past_reserve');
//Route::get('/register_stylist','StylistController@register_stylist');
Route::get('/able_time','StylistController@able_time');
Route::get('/reserved', 'MailController@reserveComplete');
Route::get('/api','ApiTestController@test');
Route::get('/salon/{$salon_id}/{$stylist}/show_review','StylistController@show_review');
Route::get('/edit', 'HomeController@edit');
Route::get('/show_location', 'HomeController@show_locationPage');

Route::post('/login', 'HomeController@start');
Route::post('/salon/{admin}/reserve_time_menu', 'ReserveController@reserve_time_menu');
Route::post('/salon/{admin}/confirm', 'HomeController@confirm');
Route::post('/salon/{admin}/reserve', 'ReserveController@reserve');
Route::post('/edit', 'HomeController@edit');
Route::post('/edit_time_menu', 'HomeController@edit_time_menu');
Route::post('/edit_confirm', 'HomeController@edit_confirm');
Route::post("/store_stylist",'StylistController@store');
Route::post('/salon/{admin}/able_time','StylistController@able_time');
Route::post('/show_create_review','StylistController@show_create_reviw');
Route::post('/create_review','StylistController@create_review');

Route::put('/update', 'HomeController@update');


Route::delete('/delete', 'HomeController@delete');




//---------------------------------------------------------------------------------------------------------------
//管理者側ルーティング



//美容師を登録するページへのルーティング
Route::get('admin/{admin}/register_stylist', 'Admin\ImageController@register_stylist');
//美容師を登録する処理のルーティング
Route::post('/admin/{admin}/upload/stylist', 'Admin\ImageController@upload');



Route::get('/admin/{admin}/info_stylist','Admin\HomeController@show_info');

//営業時間設定
Route::get('/admin/{admin}/config_time','Admin\HomeController@show_timePage');
Route::post('/admin/{admin}/config_time','Admin\TimeController@config_time');

//メニュー設定
Route::get('/admin/{admin}/config_menu','Admin\HomeController@show_menuPage');
Route::post('/admin/{admin}/config_menu','Admin\MenuController@config_menu');

//カレンダーページ
Route::get('/show_calender', 'Admin\HomeController@show_calender');

Route::post('/admin_Login','Admin\LoginController@adminLogin');
Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    // ログイン認証関連
    // Auth::routes([
    //     'register' => true,
    //     'reset'    => false,
    //     'verify'   => false
    // ]);

    Route::get('/login_corporation', 'Admin\Auth\LoginController@show_LoginForm');
    
    // ログイン認証後
    Route::middleware('auth:admin')->group(function () {

        // TOPページ
        Route::get('/home','Admin\HomeController@index');
        Route::get('/logout','Admin\Auth\LoginController@logout')->name('admin.logout');
    });

});

Route::get('/admin_access', 'Admin\HomeController@admin_access');
Route::get('/edit_access', 'Admin\HomeController@edit_accessForm');