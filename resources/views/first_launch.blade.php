@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-8" >
            
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
        <div>
            <div class = "search"　>
                <form method="POST", action="/salon/search_admin">
                    @csrf
                    <input type = "text" name = "search[salonName]" placeholder = "美容院名">
                    <input type = "submit" value = "検索">
                </form>
            </div>
            <hr>
            <div class = "search[]"　>
                <form method="POST", action="/salon/search_region">
                    @csrf
                    <input type = "radio" name = "search[]" value="北海道" >北海道
                    <input type = "radio" name = "search[]" value="東北" style="margin-left:10px">東北
                    <br>
                    <input type = "radio" name = "search[]" value="北信越">北信越
                    <input type = "radio" name = "search[]" value="関東" style="margin-left:10px">関東　
                    <br>
                    <input type = "radio" name = "search[]" value="東海">東海
                    <input type = "radio" name = "search[]" value="中国" style="margin-left:24px">中国
                    <br>
                    <input type = "radio" name = "search[]" value="関西">関西
                    <input type = "radio" name = "search[]" value="四国" style="margin-left:24px">四国
                    <br>
                    <input type = "radio" name = "search[]" value="九州・沖縄"　>九州・沖縄
                    <br>
                    <input type = "submit" value = "絞り込む" style="margin-top:10px">
                    <hr>
                </form>
            </div>
            
        </div>
    </div>
    <br>
    
</div>
@endsection
