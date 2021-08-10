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

                    <form action = "/salon/{{ $reserve->id }}/edit_time_menu" method = "POST">
                        @csrf
                        <div class = "date">
                            <p>1:ご変更希望日時を選択してください</p>
                            <input type = "date" name = "edit[date]" value = {{$reserve->date}} required>
                        </div>
                        
                        <hr>
                        
                        <div class = "stylist">
                            <p>2:美容師を変更する場合は選択してください　<a href = "/salon/{{ $reserve->admin_id }}/info_stylist">※美容師の詳細はこちら</a></p>
                            <select name = "edit[stylist]" required>
                                <option value = "">選択してください</option>
                                @foreach ($stylists as $stylist)
                                    @if ($reserve->stylist_id == $stylist->id)
                                        <option value = {{ $stylist->name }} selected>{{ $stylist->name }}</option>
                                    @else
                                        <option value = {{ $stylist->name }}>{{ $stylist->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        
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
