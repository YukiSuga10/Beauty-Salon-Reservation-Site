@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">マイページ</div>

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
                    <h5 style="border-bottom: 3px double #7C7B7B; margin:16px; padding:5px;">{{ $user->name }}さん</h5>
                    <ul>
                        <li><a class = "btn_item" href = "/salon/mypage/edit_profile">プロフィール編集</a></li>
                        <hr style="margin:10px;">
                        <li><a class = "btn_item" href = "/salon/mypage/confirm">予約確認</a></li>
                    </ul>
                    
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
