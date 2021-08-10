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
                    <form method ="POST" action="/salon/{{ $reserve->id }}/edit_confirm">
                        @csrf
                        <div class = "time">
                            <p>3:ご変更可能可能時間</p>
                            <select name = "edit[time]" required>
                                <option value = "">選択してください</option>
                                @foreach ($times as $time)
                                    @if ($reserve->startTime == $time)
                                        <option value = {{ $time }} selected>{{ $time }}~</option>
                                    @else
                                        <option value = {{ $time }}>{{ $time }}~</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class = "menu" >
                            <p>4:メニューを選択してください</p>
                            <select name = "edit[menu]" id = "menu" required>
                                <option value = "">選択してください</option>
                                @foreach ($menus as $menu);
                                    @if ($reserve->menu == $menu)
                                        <option value = {{ $menu }} selected>{{ $menu }}</option>
                                    @else
                                        <option value = {{ $menu }}>{{ $menu }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <input type = "submit" value = "変更する"> 
                        <p></p>
                        <div class='back'>[<a href='/edit'>戻る</a>]</div>
                    </form>
    
    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
