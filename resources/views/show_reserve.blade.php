@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">予約詳細</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    
                    <div class = "date">
                        <p>{{ $reserve->admin->name }}<p>
                    </div>
                    <div class = "date">
                        <p>日付 : {{ $reserve->date }}<p>
                    </div>
                    
                    <div class = "time">
                        <p>来店時間 : {{ $reserve->startTime }} (所要時間 : {{$time_required}}程度)</p>
                    </div>
                    <div class = "stylist">
                       <p>スタイリスト : {{ $stylist->name }} </p>
                    </div>
                    <div class = "menu">
                       <p>メニュー：{{ $reserve->menu }}</p>
                    </div>
                    <hr>
                    @if ($last == $reserve->id)
                        <div style = "display:inline-flex">
                            <form action = "/salon/{{ $reserve->id }}/edit" method = "POST">
                                {{ csrf_field() }}
                                
                                
                                <input type = "submit" value = "予約変更">
                            </form>
                            <form action = '/salon/{{ $reserve->id }}/delete' method = "POST">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                
                                
                                <input type = "submit" value = "予約キャンセル" id = "reserve" onclick = "return deleteReserve(this);">
                            </form>
                        </div>
                    @else
                    <form method = 'POST', action = '/salon/{{ $reserve->id }}/review'>
                        {{ csrf_field() }}
                        <input type = 'submit' value = 'レビューを書く'>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
