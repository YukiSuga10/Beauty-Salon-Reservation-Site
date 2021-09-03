@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アクセス</div>
                
                <div id="nav">
                    <ul>
                        <li><a href = '/salon/{{$salon->id}}'><i class="fas fa-home"></i> ホーム</a></li>
                        <li><a href = '/salon/{{$salon->id}}/info_stylist'><i class="fas fa-cut"></i> スタイリスト</a></li>
                        <li><a href = '/salon/{{$salon->id}}/salon_review'><i class="fas fa-comment-dots"></i> 口コミ</a></li>
                        <li class="current"><a href = '/salon/{{$salon->id}}/show_location'><i class="fas fa-map-marker-alt"></i> アクセス</a></li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <p>[住所]</p>
                    <p id = "address">{{ $address }}</p>
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
                    
                    <p id="salonName" hidden>{{$salon->name}}</p>
                   <div id="map" style="height:500px"></div>
                    <script src="{{ asset('/js/result.js') }}"></script>
                　  <script src= "https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyBLDbSsnWgTPX3-l8E6eP-2NJPdFww_ZI0&callback=initMap" async defer>
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
