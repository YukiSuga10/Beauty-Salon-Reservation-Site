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
                            <textarea rows="3" cols="60" name = "introduction[]">{{ $introduction }}</textarea><hr>
                        @else
                            <textarea rows="3" cols="60" name = "introduction[]"></textarea><hr>
                        @endif
                        
                        
                        
                        <h6>~美容院紹介画像（3枚まで可）~</h6><br>
                        @if (count($images)==3)
                            <p>※登録できる写真は3枚までです。</p>
                            <p>画像変更の場合は、以下の写真を消してください</p>
                            
                            <br>
                            <div class="salon_image">
                            @foreach ($images as $key => $image)
                                @if ($key == 0)
                                    <input type="radio" name="sizeSelect" value="small" id="sizeSelectSmall" checked><label for="sizeSelectSmall" style="background-image: url({{$image->path}}); background-size:cover;"></label>
                                @elseif($key == 1)
                                    <input type="radio" name="sizeSelect" value="medium" id="sizeSelectMedium"><label for="sizeSelectMedium" style="background-image: url({{$image->path}}); background-size:cover;"></label>
                                @else
                                    <input type="radio" name="sizeSelect" value="large" id="sizeSelectLarge"><label for="sizeSelectLarge" style="background-image: url({{$image->path}}); background-size:cover;"></label>
                                @endif
                            @endforeach
                            </div>
                            
                            <form action = '/admin/{{ $id }}/{{$image->id}}/delete' method = "POST">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                
                                
                                <input type = "submit" value = "削除" id = "salon_image" onclick = "return deleteReserve(this);">
                            </form>
                        @else
                            <input type="file" name="photo[]" multiple="multiple">
                        @endif
                        
                        <hr>
                        
                        @if (isset($introduction) || count($images)==3)
                            <input type = "submit" value = "変更">
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
