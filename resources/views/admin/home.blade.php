@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$name}}管理画面</div>

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
                        <a href = '/admin/{{ $salon_id }}/register_stylist'>▶美容師の登録</a>
                    </div>

                    <hr>
                    <div class = 'confirm-reserve'>
                        <a href = '/admin/{{ $salon_id }}/info_stylist'>▶美容師の確認</a>
                    </div>
                    <hr>
                    <div class = 'config-time'>
                        <a href = '/admin/{{ $salon_id }}/config_time'>▶営業時間の設定</a>
                    </div>
                    <hr>
                    <div class = 'config-menu'>
                        <a href = '/admin/{{ $salon_id }}/config_menu'>▶メニューの設定</a>
                    </div>
                    <hr>
                    <div class = 'confirm_calender'>
                        <a href = '/admin/{{ $salon_id }}/show_calender'>▶予約カレンダーの確認</a>
                    </div>
                    <hr>
                    <div class = 'edit_access'>
                        <a href = '/admin/{{ $salon_id }}/admin_access'>▶アクセスの編集</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
