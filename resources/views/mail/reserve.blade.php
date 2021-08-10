<!DOCTYPE html>
<html lang="ja">
    <body>
        <p>{{$text}}</p></br>
        <p>ご予約ありがとうございました！</p>
        <p>▶︎ご予約内容</p>
        <div class = "content">
            <p>日付：{{ $reserves['date'] }}</p>
            <p>時間：{{ $reserves['time'] }}〜</p>
            <p>担当スタイリスト：{{ $reserves['stylist'] }}</p>
            <p>メニュー：{{ $reserves['menu'] }}</p>
        </div>
    
        </br>
        <p>ご来店お待ちしております！</p>
    </body>
</html>