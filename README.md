<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## タイトル

美容院予約サイトです。<br>
様々な美容院において予約や各美容院についての情報を見ることができます。<br>
アピールポイント

- サイトURL<br>
    https://salon-reserve.herokuapp.com/
    
    - テスト用ユーザー<br>
        <ユーザー側><br>
        ログインID : test@gmail.com<br>
        パスワード : test1234<br>
        
        <美容院側><br>
        ログインID : salonA@gmail.com<br>
        パスワード : salonA1234<br>
        
## 使用技術

- PHP 7.3.27
- Laravel Framework 6.20.27
- MariaDB 10.5.12
- AWS
    - EC2
    - S3
- Google Maps API

## AWS構造図



## 機能一覧

<ユーザー側>
- 新規登録、ログイン機能
- 予約機能
- 予約の確認、変更、キャンセル機能
- レビュー投稿機能
- 美容院検索機能（美容院名 ・ 地域）

<美容院側>
- 新規登録、ログイン機能
- 新規スタイリストの登録
- スタイリストの確認、編集
- 各種設定
    - 営業時間
    - メニュー
    - 住所
- 美容院の紹介文、紹介画像登録 
