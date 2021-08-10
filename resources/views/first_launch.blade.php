@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" >
            
            <div class = "search"　>
                <input type = "text" placeholder = "美容院名">
                <input type = "submit" value = "検索">
            </div>
            
            <div class="card">
                <div class="card-header">ようこそ！！</div>

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
                    <div class = 'show_salon'>
                        <p><h5>▶サロン一覧</h5></p>
                        @foreach($admins as $admin)
                            <p><a href = "/salon/{{$admin->id}}">{{ $admin->name }}</a></p>
                        @endforeach
                    </div>
                    <hr>
                    <div>
                        <p><h5>▶予約確認</h5></p>
                        <a href = "/salon/mypage">予約確認はこちらから</a>
                    </div>
                    <br>
                
                </div>
            </div>
        </div>
    </div>
    <br>
    
</div>
@endsection
