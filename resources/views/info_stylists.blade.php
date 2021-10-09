@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color:#fff1e0;">スタイリスト一覧</div>
                <div id="nav">
                    <ul>
                        <li><a href = '/salon/{{$salon_id}}'><i class="fas fa-home"></i> ホーム</a></li>
                        <li class="current"><a href = '/salon/{{$salon_id}}/info_stylist'><i class="fas fa-cut"></i> スタイリスト</a></li>
                        <li><a href = '/salon/{{$salon_id}}/salon_review'><i class="fas fa-comment-dots"></i> 口コミ</a></li>
                        <li><a href = '/salon/{{$salon_id}}/show_location'><i class="fas fa-map-marker-alt"></i> アクセス</a></li>
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
                    <p>空き状況検索はこちらから</p>
                    <form method ="POST" action='/salon/{{$salon_id}}/able_time'>
                                @csrf
                                <div style = "display:inline-flex">
                                    <input type = "date" name = "search[date]" value = "{{ old('search[date]') }}" required>
                                    <input type = 'submit' value = '検索する'>
                                </div>
                    <hr>
                        @foreach ($stylists as $stylist)
                        <div class="each_stylist">
                            <img src="{{ $stylist->file_images->path }}">
                            <div class="stylist_info">
                            <p>スタイリスト名：{{ $stylist->name }}</p>
                            <p>性別：{{ $stylist->gender }}</p>
                            <p class="review"><a href = "/salon/{{ $salon_id }}/{{ $stylist->id }}/show_review">この美容師の口コミを見る</a></p>
                            </div>
                        </div>
                            @if(@count($stylist_times) == 0)
                                <hr>
                            @else
                            <div class="avail_stylist">
                                <p class="info_date">〜{{$date}}日の空き状況〜</p>
                                <p class="info">○＝空き</p>
                                @foreach($stylist_times as $key => $stylist_time)
                                    @if($key == $stylist->name)
                                    <div class="able_time">
                                        <table border="1">
                                            <tr>
                                                @foreach($times as $time)
                                                    <td>{{@date("H:i",@strtotime($time))}}</td>
                                                @endforeach
                                                <tr>
                                                    @foreach($stylist_time as $condition)
                                                        <td>{{ $condition }}</td>
                                                    @endforeach
                                                </tr>
                                            </tr>
                                        </table>
                                    </div>
                                    @else
                                        @continue
                                    @endif
                                @endforeach
                            </div>
                                <hr>
                            @endif
                        
                        @endforeach
                    </form>
                    <div class = "back">
                        <input type="button" onclick="window.history.back();" value="戻る">
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection