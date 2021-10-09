@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
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
                    <h5>{{$reserve->user->name}}様</h5>
                    <hr>
                    <div class="reserve_content">
                        <p class="date">日時：{{@date("Y年m月d日",@strtotime($reserve->date))}}</p>
                        <p class="time">来店時刻：{{@date("H時i分",@strtotime($reserve->startTime))}}</p>
                        <p class="reserve_content">メニュー：{{$reserve->menu}}</p>
                        <p></p>
                    </div>
                    <div class = "back">
                        <input type="button" onclick="window.history.back();" value="戻る">
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

