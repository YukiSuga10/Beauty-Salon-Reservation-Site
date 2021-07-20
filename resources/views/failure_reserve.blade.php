@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ご予約いただけない件について</div>

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
                    @if ($date_correction == true)
                    <p>申し訳ございません</p>
                    <p>ご指定していただいた日時は埋まってしまっております。</p>
                    <p>以下からご予約し直すか、美容師の空き状況について確認ください</p>
                    
                    <p></p>
                    <div style = "display:inline-flex">
                        <p>※ご予約し直す場合はこちら⇨</p>
                        <a href = '/reserve'>予約画面へ</a>
                    </div>
                    <p></p>
                    <div style = "display:inline-flex">
                        <p>※美容師の空き状況はこちら⇨</p>
                        <a href = '/info_stylist'>美容師の空き状況</a>
                    </div>
                    
                    @else
                    <p>申し訳ございません</p>
                    <p>ご指定していただいた日にちは過去の日にちのため予約できません</p>
                    <p>以下からご予約し直すか、美容師の空き状況について確認ください</p>
                    
                    <p></p>
                    <div style = "display:inline-flex">
                        <p>※ご予約し直す場合はこちら⇨</p>
                        <a href = '/reserve'>予約画面へ</a>
                    </div>
                    <p></p>
                    <div style = "display:inline-flex">
                        <p>※美容師の空き状況はこちら⇨</p>
                        <a href = '/info_stylist'>美容師の空き状況</a>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>


@endsection