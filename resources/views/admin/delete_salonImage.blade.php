@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">登録画像一覧</div>
                
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
                            <hr>
                    @endif

                    <main class="mt-4">
                        @yield('content')
                    </main>
                    <form method="POST" name="a_form" action="/admin/{{ $id }}/delete">
                        @csrf
                        @method('delete')
                         <div class="salon_image">
                            @foreach ($images as $key => $image)
                                @if ($key == 0)
                                        <input type="radio" name="image_id" value="{{$image->id}}" id="sizeSelectSmall" checked><label for="sizeSelectSmall" style="background-image: url({{$image->path}}); background-size:cover;"></label>
    
                                    @elseif($key == 1)
                                        <input type="radio" name="image_id" value="{{$image->id}}" id="sizeSelectMedium"><label for="sizeSelectMedium" style="background-image: url({{$image->path}}); background-size:cover;"></label>
    
                                    @else
                                        <input type="radio" name="image_id" value="{{$image->id}}" id="sizeSelectLarge"><label for="sizeSelectLarge" style="background-image: url({{$image->path}}); background-size:cover;"></label>
    
                                    @endif
                            @endforeach
                        
                        </div>
                        <input type = "submit" id = "salon_image" style = "border-radius: 30px;" onclick = "return deleteSalonImage(this);" value = "削除する">
                    </form>

            </div>
        </div>
    </div>
</div>
@endsection
