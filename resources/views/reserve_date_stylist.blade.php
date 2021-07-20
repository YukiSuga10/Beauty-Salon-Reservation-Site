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
                    <form method ="POST" action="/reserve_time_menu">
                        @csrf
                        <div class = "date">
                            <p>1:カレンダーから日付を選択してください</p>
                            <input type = "date" name = "reserve[date]" required>
                        </div>
                         
                        <hr>
                         
                        <div class = "stylist">
                            <p>2:美容師を選択してください　<a href = "/info_stylist">※美容師の詳細はこちら</a></p>
                            <select name = "reserve[stylist]" id = "stylist" required>
                                <option value = "">選択してください</option>
                                @foreach ($stylists as $stylist)
                                    <option value = {{ $stylist }}>{{ $stylist }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <input type = "submit" value = "次へ">
                        <p></p>
                        <div class='back'>[<a href='/'>戻る</a>]</div>
                    </form>
    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
