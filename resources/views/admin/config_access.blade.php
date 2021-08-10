@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アクセスの設定</div>
                <div class="card-body">
                    

                
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    美容院の住所を設定してください
                    @if (session('flash_message'))
                            <div class="flash_message">
                                {{ session('flash_message') }}
                            </div>
                    @endif
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    <hr>
                    @if( $address == null )
                        <form action = "/admin/{{$id}}/set_address" method = "POST">
                            @csrf
                            <div>
                            郵便番号:
                            <input type="text" name="郵便番号" onKeyUp="AjaxZip3.zip2addr(this,'','住所','住所');">
                            </div>
                            <br>
                            住所:
                            <input type="text" name="住所" size="40" id = "address" required>
                            <hr>
                            <input type="submit" value = "登録">
                        </form>
                    @else
                        <form action = "/admin/{{$id}}/set_address" method = "POST">
                            @csrf
                            <div>
                            郵便番号:
                            <input type="text" name="郵便番号" onKeyUp="AjaxZip3.zip2addr(this,'','住所','住所');">
                            </div>
                            <br>
                            住所:
                            <input type="text" name="住所" size="40" value= "{{ $address }}" required>
                            <hr>
                            <input type="submit" value = "変更">
                        </form>
                        <br>
                        <p id = "address" hidden>{{ $address }}</p>
                        <p id = "salonName" hidden>{{$salon->name}}</p>
                        <div id="map" style="height:500px"></div>
                        <script src="{{ asset('/js/result.js') }}"></script>
                        <script src= "https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyBLDbSsnWgTPX3-l8E6eP-2NJPdFww_ZI0&callback=initMap" async defer>
                        </script>
                    @endif
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
