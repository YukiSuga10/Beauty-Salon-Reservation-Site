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
                    <form action="/admin/{{$id}}/upload/introduction" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h6>~美容院紹介文~</h6><br>
                        
                        @if (isset($introduction))
                            <textarea rows="3" cols="60" class = "introduction" name = "introduction[]">{{ $introduction }}</textarea><hr>
                        @else
                            <textarea rows="3" cols="60" class = "introduction" name = "introduction[]"></textarea><hr>
                        @endif
                        
                        
                        
                        <h6>~美容院紹介画像（3枚まで可）~</h6><br>
                        @if (count($images)==3)
                            <p style="color:red;">※登録できる写真は3枚までです。　<a href="/admin/{{$id}}/deletePage">画像の削除はこちらから</a></p>
                            
                            <br>
                            <div class="salon_image">
                            @foreach ($images as $image)
                                <img src="{{ $image->path }}"　width="70" height = "120">
                            @endforeach
                            </div>
                        @else
                            @foreach ($images as $image)
                                <img src="{{ $image->path }}"　width="70" height = "120">
                            @endforeach
                            <br>
                            <input type="file" id="file_btn1" name="photo[]" multiple="multiple">
                            <a href="/admin/{{$id}}/deletePage"><画像の削除はこちらから></a>
                        @endif
                        
                        <hr>
                        
                        @if (isset($introduction) || count($images)==3)
                            <input type = "submit" value = "変更" onclick = "return maxfile_confim_1(this);">
                        @else
                            <input type = "submit" value = "登録">
                        @endif
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
