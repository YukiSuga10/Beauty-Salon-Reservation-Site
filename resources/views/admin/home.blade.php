@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">企業管理画面</div>

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
                        <a href = '/register_stylist'>美容師の登録</a>
                    </div>
                    <hr>
                    <div class = 'confirm-reserve'>
                        <a href = '/admin_info_stylist'>美容師の確認</a>
                    </div>
                    <hr>
                    <div class = 'confirm_calender'>
                        <a href = 'show_calender'>予約カレンダーの確認</a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
