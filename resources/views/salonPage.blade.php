@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color:#fff1e0;">{{$salon->name}}</div>
                
                <div id="nav">
                    <ul>
                        <li class="current"><a href = '/salon/{{$salon->id}}'><i class="fas fa-home"></i> ホーム</a></li>
                        <li><a href = '/salon/{{$salon->id}}/info_stylist'><i class="fas fa-cut"></i> スタイリスト</a></li>
                        <li><a href = '/salon/{{$salon->id}}/salon_review'><i class="fas fa-comment-dots"></i> 口コミ</a></li>
                        <li><a href = '/salon/{{$salon->id}}/show_location'><i class="fas fa-map-marker-alt"></i> アクセス</a></li>
                    </ul>
                </div>
                
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
                    
                    
                    <div align = "center">
                        <div class="main_imgBox">
                            @if (count($images) == 0)
                                <label style="color:#282828; margin-top:10px;">※画像はありません。</label>
                            @else
                                @foreach ($images as $image)
                                <div class="main_img">    
                                        <img src="{{ $image }}">
                                </div>    
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class = 'new-reserve'>
                        <a href = '/salon/{{$salon->id}}/reserve'>▶︎新規予約はこちら</a>
                    </div>
                    <hr>
                    <div>
                        
                    </div>
                    
                    <div class = "back">
                        <input type="button" onclick="location.href='/home';" value="戻る">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
