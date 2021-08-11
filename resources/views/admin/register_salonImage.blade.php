@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">美容院紹介</div>
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
                    <form action="/admin/{{$id}}/upload/stylist" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h6>~美容院紹介文~</h6><br>
                        <textarea rows="10" cols="60"></textarea><hr>
                        <h6>~美容院紹介画像（3枚まで可）~</h6><br>
                        <input type="file" name="salon[photo]" multiple>
                        <hr>
                        <input type = "submit" value = "登録">
                    </form>
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
