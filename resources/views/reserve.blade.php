@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">予約フォーム</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('flash_message'))
                            <div class="flash_message">
                                {{ session('flash_message') }}
                            </div>
                    @endif
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    <form method ="POST" action="/reserve">
                        @csrf
                         <div class = "date">
                            <p>1:カレンダーから日付を選択してください</p>
                            <input type = "date" name = "reserve[date]" required>
                         </div>
                         <p></p>
                         <div class = "time">
                            <p>2:時間を選択してください</p>
                            <select name = "reserve[time]" required>
                                <option value = "">選択してください</option>
                                <option value = "9:00">9:00~</option>
                                <option value = "9:30">9:30~</option>
                                <option value = "10:00">10:00~</option>
                                <option value = "10:30">10:30~</option>
                                <option value = "11:00">11:00~</option>
                                <option value = "11:30">11:30~</option>
                                <option value = "12:00">12:00~</option>
                                <option value = "12:30">12:30~</option>
                                <option value = "13:00">13:00~</option>
                                <option value = "13:30">13:30~</option>
                                <option value = "14:00">14:00~</option>
                                <option value = "14:30">14:30~</option>
                                <option value = "15:00">15:00~</option>
                                <option value = "15:30">15:30~</option>
                                <option value = "16:00">16:00~</option>
                                <option value = "16:30">16:30~</option>
                                <option value = "17:00">17:00~</option>
                                <option value = "17:30">17:30~</option>
                                <option value = "18:00">18:00~</option>
                                <option value = "18:30">18:30~</option>
                                <option value = "19:00">19:00~</option>
                                <option value = "19:30">19:30~</option>
                                <option value = "20:00">20:00~</option>
                            </select>
                         </div>
                        <p></p>
                        <div class = "stylist">
                            <p>3:美容師を選択してください　<a href = "/info_stylist">※美容師の詳細はこちら</a></p>
                            <select name = "reserve[stylist]" required>
                                <option value = "">選択してください</option>
                                <option value ="美容師A">美容師A</option>
                                <option value ="美容師B">美容師B</option>
                                <option value ="美容師C">美容師C</option>
                            </select>
                        </div>
                        <p></p>
                        <div class = "menu" >
                            <p>4:メニューを選択してください</p>
                                <input type = "radio" name = "reserve[menu]" value = 1>カット
                                <input type = "radio" name = "reserve[menu]" value = 2>カラー
                                <input type = "radio" name = "reserve[menu]" value = 3>パーマ
                                <input type = "radio" name = "reserve[menu]" value = 4>カット+カラー
                                <input type = "radio" name = "reserve[menu]" value = 5>カット+パーマ
                        </div>
                        <p></p>
                        <input type = "submit" value = "予約する"> 
                    </form>
    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
