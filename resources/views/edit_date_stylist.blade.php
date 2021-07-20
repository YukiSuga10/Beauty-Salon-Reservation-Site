@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">予約変更画面</div>
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

                    <form action = "/edit_time_menu" method = "POST">
                        @csrf
                        <div class = "date">
                            <p>1:ご変更希望日時を選択してください</p>
                            <input type = "date" name = "edit[date]" value = {{$reserve['date']}} required>
                        </div>
                        <hr>
                        <div class = "stylist">
                            <p>2:美容師を変更する場合は選択してください　<a href = "/info_stylist">※美容師の詳細はこちら</a></p>
                            <select name = "edit[stylist]" required>
                                <option value = "">選択してください</option>
                                @foreach ($stylists as $stylist)
                                    @if ($reserve['stylist'] == $stylist->name)
                                        <option value = {{ $stylist->id }} selected>{{ $stylist->name }}</option>
                                    @else
                                        <option value = {{ $stylist->id }}>{{ $stylist->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- 隠しデータ送信 --}}
                        <input type = 'hidden' name = 'edit[time]' value = {{ $reserve['time'] }}>
                        <input type = 'hidden' name = 'edit[menu]' value = {{ $reserve['menu'] }}>
    
                        <hr>
                        <input type = "submit" value = "次へ">
                        <p></p>
                        <div class='back'>[<a href='/mypage'>戻る</a>]</div>
                    </form>
    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
