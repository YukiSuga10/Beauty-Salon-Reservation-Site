@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">確認画面</div>
                <div class="card-body">
                    @if ($content == "予約確認")
                    以下のご予約内容でよろしいですか
                    @elseif($content == "変更確認")
                    以下の変更内容でよろしいですか
                    @endif
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
                    @if ($content == '予約確認')
                    <form method ="POST" action="/salon/{{$salon_id}}/reserve">
                        @csrf
                        
                        {{-- 隠しデータ送信 --}}
                        <input type = 'hidden' name = 'reserve[date]' value = {{ $reserve['date']}}>
                        <input type = 'hidden' name = 'reserve[time]' value = {{ $reserve['time']}}>
                        <input type = 'hidden' name = 'reserve[stylist]' value = {{ $reserve['stylist']}}>
                        <input type = 'hidden' name = 'reserve[menu]' value = {{ $reserve['menu']}}>
                        
                        <div class = "time">
                            <p>{{ $salon->name }}</p>
                        </div>
                        
                        <div class = "date">
                            <p name = "reserve">日付 : {{ $date }}<p>
                        </div>
                        
                        <div class = "time">
                            <p>来店時間 : {{ $time }} (所要時間 : {{$time_require}}程度)</p>
                        </div>

                        <div class = "stylist">
                           <p>スタイリスト : {{ $reserve["stylist"] }} </p>
                        </div>

                        <div class = "menu">
                           <p>メニュー : {{ $reserve['menu'] }}</p>
                        </div>
                        <hr>
                        <input type = "submit" value = "予約する">
                    </form>
                    @else
                    <form method ="POST" action="/update">
                        @csrf
                        @method('PUT')
                        {{-- 隠しデータ送信 --}}
                        <input type = 'hidden' name = 'edit[date]' value = {{ $edit['date']}}>
                        <input type = 'hidden' name = 'edit[time]' value = {{ $edit['time']}}>
                        <input type = 'hidden' name = 'edit[stylist]' value = {{ $edit['stylist']}}>
                        <input type = 'hidden' name = 'edit[menu]' value = {{ $edit['menu']}}>
                        
                        <div class = "date">
                            <p name = "reserve">日付 : {{ $date }}<p>
                        </div>
                        
                        <div class = "time">
                            <p>来店時間 : {{ $time }} (所要時間 : {{$time_require}}程度)</p>
                        </div>

                        <div class = "stylist">
                           <p>スタイリスト : {{ $edit["stylist"] }} </p>
                        </div>

                        <div class = "menu">
                           <p>メニュー : {{ $menu }}</p>
                        </div>
                        <hr>
                        <input type = "submit" value = "変更する">
                        
                    </form>
                    @endif
                    <br>
                    <div class='back'>[<a href='javascript:history.back()'>戻る</a>]</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
