@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$salon->name}}</div>

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
                        <a href = '/salon/{{$salon->id}}/reserve'>▶︎新規予約はこちら</a>
                    </div>
                    <br>
                    <div class = 'info_stylist'>
                        <a href = '/salon/{{$salon->id}}/info_stylist'>▶美容師の詳細はこちら</a>
                    </div>
                    <br>
                    <div class = 'location'>
                        <a href = '/salon/{{$salon->id}}/show_location'>▶アクセス</a>
                    </div>
                    <hr>
                    <div class = "back">
                        <input type="button" onclick="window.history.back();" value="戻る">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
