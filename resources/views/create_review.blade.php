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
                    <form action='/create_review' method='POST'>
                        @csrf
                        <div class="star">
                            <h5>評価</h5>
                                @foreach ($stars as $key => $value)
                                    <input type = 'radio' name = 'review[evaluation]' value = {{$key}} required>{{$key}}：{{$value}}</br>
                                @endforeach
                        </div>
                        <p></p>
                        <div class="comment">
                            <h5>コメント</h5>
                            <textarea name = 'review[comment]' ></textarea>
                        </div>
                        
                        {{-- 隠しデータ送信 --}}
                        <input type = 'hidden' name = 'review[date]' value = {{ $reserves['date']}}>
                        <input type = 'hidden' name = 'review[time]' value = {{ $reserves['time']}}>
                        <input type = 'hidden' name = 'review[stylist]' value = {{ $reserves['stylist']}}>
                        <input type = 'hidden' name = 'review[menu]' value = {{ $reserves['menu']}}>
                        
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
