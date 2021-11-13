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


Route::get('/login/google', 'Auth\LoginController@redirectToGoogle');
Route::get('/login/google/callback', 'Auth\LoginController@handleGoogleCallback');

//ユーザ側ルーティング
Route::get('/', 'HomeController@show_salon');
Route::get('/home', 'HomeController@show_salon');
Route::get('/salon/mypage', 'HomeController@show_mypage');
Route::get('/register', 'RegisterController@show_RegisterForm');
Route::get('/salon/search_admin','SearchController@result_salon');
Route::get('/show_salon', 'HomeController@show_salon');
Route::get('/salon/mypage/confirm','HomeController@reserve_confirm');
Route::get('/salon/mypage/edit_profile','HomeController@show_profile');
Route::get('/salon/{admin}','HomeController@show_salonPage');
Route::get('/salon/{admin}/info_stylist','HomeController@info_stylist');
Route::get('/salon/{admin}/reserve', 'ReserveController@reserve_date_stylist');
Route::get('/salon/{admin}/reserve_time_menu', 'ReserveController@reserve_time_menu');
Route::get('/salon/{admin}/salon_review', 'HomeController@reviewPage');
Route::get('/salon/mypage/show_reserve/{reserve}', 'HomeController@show_reserve');
Route::get('/salon/show_reserve/{reserve}/past', 'HomeController@show_reserve');
Route::get('/salon/mypage/past_reserve/{user}', 'HomeController@past_reserve');
Route::get('/able_time','StylistController@able_time');
Route::get('/reserved', 'MailController@reserveComplete');
Route::get('/api','ApiTestController@test');
Route::get('/salon/{admin}/{stylist}/show_review','StylistController@show_review');
Route::get('/salon/{admin}/show_location', 'GmapsController@show_maps');

//検索機能
Route::post('/salon/search_admin','SearchController@result_salon');
Route::post('/salon/search_region','SearchController@result_region');


Route::post('/login', 'HomeController@start');
Route::post('/salon/{admin}/reserve_time_menu', 'ReserveController@reserve_time_menu');
Route::post('/salon/{admin}/confirm', 'ReserveController@confirm');
Route::post('/salon/{admin}/reserve', 'ReserveController@reserve');
Route::post('/salon/{reserve}/edit', 'HomeController@show_editForm');
Route::post('/salon/{reserve}/edit_time_menu', 'HomeController@edit_time_menu');
Route::post('/salon/{reserve}/edit_confirm', 'HomeController@edit_confirm');
Route::post("/store_stylist",'StylistController@store');
Route::post('/salon/{admin}/able_time','StylistController@able_time');
Route::post('/salon/{reserve}/review','StylistController@show_create_reviw');
Route::post('/salon/{reserve}/review/create','StylistController@create_review');
Route::post('/salon/{admin}/salon_review','SearchController@refine_review');





Route::put('/salon/{reserve}/update', 'MailController@editComplete');
Route::put('/salon/profile/edit', 'HomeController@editProfile');


Route::delete('/salon/{reserve}/delete', 'HomeController@delete');

Auth::routes();


//---------------------------------------------------------------------------------------------------------------
//管理者側ルーティング

Route::get('/login_corporation', 'Admin\LoginController@show_LoginForm')->name('admin.login');
Route::get('/launch_corporation', 'Admin\LoginController@show_LoginForm');
Route::get('/register_corporation', 'Admin\RegisterController@show_RegisterForm')->name('admin.register');;
Route::post('/admin/register','Admin\RegisterController@createAdmin');




Route::post('/admin_Login','Admin\LoginController@adminLogin');
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function() {
    Route::post('/logout','Admin\LoginController@logout')->name('admin.logout');;
    Route::get('/home','Admin\HomeController@index')->name('admin.home');
    Route::get('/admin_info_stylist', 'Admin\HomeController@show_info');
    Route::get('/{admin}/config_menu','Admin\HomeController@show_menuPage');
    
    Route::get('/{admin}/info_stylist','Admin\HomeController@show_info');
    
    //美容師を登録するページへのルーティング
    Route::get('/{admin}/register_stylist','Admin\ImageController@register_stylist');
    
    //美容師を登録する処理
    Route::post('/{admin}/upload/stylist', 'Admin\ImageController@upload');
    
    //営業時間設定
    Route::get('/{admin}/config_time','Admin\HomeController@show_timePage');
    Route::post('/{admin}/config_time','Admin\TimeController@config_time');
    
    //メニュー設定
    Route::get('/{admin}/config_menu','Admin\HomeController@show_menuPage');
    Route::post('/{admin}/config_menu','Admin\MenuController@config_menu');
    
    //アクセス設定
    Route::get('/{admin}/admin_access', 'Admin\HomeController@show_configAccess');
    Route::post('/{admin}/set_address','Admin\GmapsController@set_address');
    
    //美容院の紹介関連
    Route::get('/{admin}/salon_images', 'Admin\HomeController@register_salonImage');
    Route::post('/{admin}/upload/introduction','Admin\HomeController@upload_introduction');
    Route::get('/{admin}/deletePage', 'Admin\HomeController@show_deletePage');
    Route::delete('/{admin}/delete', 'Admin\HomeController@deleteImage');
    
    //カレンダーページ
    Route::get('/{admin}/show_calender', 'Admin\CalendarController@show_calender');
    Route::get('/reserve/{reserve}', 'Admin\CalendarController@show_reserve');
    Route::get('/{admin}/pre/{date}', 'Admin\CalendarController@pre_month');
    Route::get('/{admin}/next/{date}', 'Admin\CalendarController@next_month');
    Route::get('/{admin}/{date}', 'Admin\CalendarController@date_reserves');
    Route::get('/{admin}/{stylist}/edit', 'Admin\HomeController@edit');
    
    
    
    //更新処理
    Route::put('/{admin}/edit_menu', 'Admin\MenuController@update_menu');
    Route::put('/{stylist}/edit', 'Admin\HomeController@update_stylist');
    
    Route::delete('/{admin}/{reserve}/delete', 'Admin\HomeController@delete');
    
});

Route::get('/admin_access', 'Admin\HomeController@admin_access');
Route::get('/edit_access', 'Admin\HomeController@edit_accessForm');