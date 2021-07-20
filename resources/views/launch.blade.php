@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ようこそ！！</div>

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
                    <div class = 'new-reserve'>
                        <a href = '/reserve'>▶︎新規予約はこちら</a>
                    </div>
                    <br>
                    <div class = 'confirm-reserve'>
                        <a href = '/mypage'>▶ご予約の確認はこちら</a>
                    </div>
                    <br>
                    <div class = 'info_stylist'>
                        <a href = '/info_stylist'>▶美容師の詳細はこちら</a>
                    </div>
                    <br>
                    <div class = 'location'>
                        <a href = '/show_location'>▶アクセス</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
