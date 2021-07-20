@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">スタイリスト一覧</div>

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
                    
                        @foreach ($stylists as $stylist)
                            @foreach ($stylist_images as $stylist_image)
                                @if ($stylist->id == $stylist_image->id)
                                    <img src="{{ $stylist_image->path }}"　width="200px" height = "200px">
                                @else
                                    <p></p>
                                @endif 
                            @endforeach
                        @endforeach

                            <p>スタイリスト名：{{ $stylist->name }}</p>
                            <p>性別：{{ $stylist->gender }}</p>
                            <p><a href = "/{{ $stylist->id }}/show_review">この美容師の口コミを見る</a></p>
                            
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
