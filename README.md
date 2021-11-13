<p align="center"><a href="https://salon-reserve.herokuapp.com/" target="_blank"><img height="500" alt="スクリーンショット 2021-11-10 17 40 42" src="https://user-images.githubusercontent.com/82369122/141079370-333b68f7-5a3f-4937-8375-c778d5f73f49.png"></a></p>


## SalonBeauty

美容院予約サイトです。<br>
様々な美容院において予約や各美容院についての情報を見ることができます。<br>
Googleからのログインも可能となっています

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
- Google+ API


## 機能一覧

<ユーザー側>
- 新規登録、ログイン機能
- 予約機能
    - 新規予約
    - 予約確認
    - 予約の変更・キャンセル  
- レビュー投稿機能
- 検索機能
    - 美容院名
    - 地域

<美容院側>
- 新規登録、ログイン機能
- 新規スタイリストの登録
- スタイリストの確認、編集
- 各種設定
    - 営業時間
    - メニュー
    - 住所
- 美容院の紹介文、紹介画像登録 