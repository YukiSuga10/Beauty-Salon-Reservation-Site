@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">レビューの投稿</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    <form action='review/create' method='POST'>
                        @csrf
                        <div class="star">
                            <h5>評価</h5>
                                @foreach ($evaluation as $key => $value)
                                    <input type = 'radio' name = 'review[evaluation]' value = {{$key}} required>{{$key}}：{{$value}}</br>
                                @endforeach
                        </div>
                        <p></p>
                        <div class="comment">
                            <h5>コメント</h5>
                            <textarea name = 'review[comment]' ></textarea>
                        </div>
                        
                        
                        
                        <input type='submit' value='投稿する'>
                    </form>
                    
                    <div id = "app">
                        <example-component></example-component>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
