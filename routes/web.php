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
Route::get('/', 'HomeController@start');
Route::get('/reserve', 'ReserveController@reserve_date_stylist');
Route::get('/info_stylist', 'HomeController@info_stylist');
Route::get('/mypage','HomeController@mypage');
Route::get('/show_reserve/{date}', 'HomeController@show_reserve');
Route::get('/past_reserve', 'HomeController@past_reserve');
//Route::get('/register_stylist','StylistController@register_stylist');
Route::get('/able_time','StylistController@able_time');
Route::get('/reserved', 'MailController@reserveComplete');
Route::get('/api','ApiTestController@test');
Route::get('/{stylist}/show_review','StylistController@show_review');
Route::get('/edit', 'HomeController@edit');
Route::get('/show_location', 'HomeController@show_locationPage');

Route::post('/login', 'HomeController@start');
Route::post('/reserve_time_menu', 'ReserveController@reserve_time_menu');
Route::post('/confirm', 'HomeController@confirm');
Route::post('/reserve', 'ReserveController@reserve');
Route::post('/edit', 'HomeController@edit');
Route::post('/edit_time_menu', 'HomeController@edit_time_menu');
Route::post("/store_stylist",'StylistController@store');
Route::post('/able_time','StylistController@able_time');
Route::post('/show_create_review','StylistController@show_create_reviw');
Route::post('/create_review','StylistController@create_review');

Route::put('/update', 'HomeController@update');

Route::delete('/delete', 'HomeController@delete');

Auth::routes();


//---------------------------------------------------------------------------------------------------------------
//管理者側ルーティング

Route::get('/login_corporation', 'Admin\LoginController@show_LoginForm')->name('admin.login');;
Route::get('/launch_corporation', 'Admin\LoginController@show_LoginForm');
Route::get('/register_corporation', 'Admin\RegisterController@show_RegisterForm')->name('admin.register');;
Route::post('/admin_register','Admin\RegisterController@register_admin');

//画像関連ルーティング
//画像ファイルをアップロードするボタンを設置するページへのルーティング
Route::get('/register_stylist', 'ImageController@register_stylist');
//画像ファイルをアップロードする処理のルーティング
Route::post('/upload/image', 'ImageController@upload');
//アップロードした画像ファイルを表示するページのルーティング
Route::get('/output/image', 'ImageController@output');
Route::get('/home','Admin\HomeController@index')->name('admin.home');

Route::get('/admin_info_stylist','Admin\HomeController@show_info');

//カレンダーページ
Route::get('/show_calender', 'Admin\HomeController@show_calender');

Route::post('/admin_Login','Admin\LoginController@adminLogin');
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function() {
    Route::post('/logout','Admin\LoginController@logout')->name('admin.logout');;
    Route::get('/home','Admin\HomeController@index')->name('admin.home');
    Route::get('/admin_info_stylist', 'Admin\HomeController@show_info');
});