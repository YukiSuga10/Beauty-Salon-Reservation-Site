@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アクセス　<a href = "edit_access">編集する</a></div>

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
                    <div id="map" style="height:500px">
            	   </div>
            	   <script src= "https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyBLDbSsnWgTPX3-l8E6eP-2NJPdFww_ZI0&callback=initMap" async defer>
            	   </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
