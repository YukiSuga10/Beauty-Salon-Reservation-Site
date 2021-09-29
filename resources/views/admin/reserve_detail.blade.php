@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
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
                    <div class="reserve">
                        <p>名前：</p>
                        <p>日時：</p>
                        <p>メニュー：</p>
                        <p></p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

