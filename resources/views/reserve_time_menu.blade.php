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
                    <form method ="POST" action="/salon/{{$salon_id}}/confirm">
                        @csrf
                         {{-- 隠しデータ送信 --}}
                        <input type = 'hidden' name = 'reserve[date]' value = {{ $reserve['date']}}>
                        <input type = 'hidden' name = 'reserve[stylist]' value = {{ $reserve['stylist']}}>
                        
                        
                        <div class = "time">
                            <p>3:ご予約可能時間</p>
                            <select name = "reserve[time]" required>
                                <option value = "">選択してください</option>
                                @foreach ($times as $time)
                                    <option value = {{ $time }}>{{ $time }}~</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class = "menu" >
                            <p>4:メニューを選択してください</p>
                            <select name = "reserve[menu]" id = "menu" required>
                                <option value = "">選択してください</option>
                                @foreach ($menus as $menu);
                                    <option value = {{$menu}}>{{$menu}}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <input type = "submit" value = "予約する"> 
                        <p></p>
                        <div class='back'>[<a href='/reserve'>戻る</a>]</div>
                    </form>
    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
