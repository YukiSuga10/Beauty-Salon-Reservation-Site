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
                    <h4>予約一覧</h4>
                        <a href = "/salon/mypage/past_reserve/{{Auth::id()}}">※過去のご利用履歴はこちら</a>
                    <hr>
                    </form>
                    
                    @if (@count($reserves) != 0)
                    <div class = "reserves">
                        @foreach ($reserves as $reserve)
                            <div class = "reserve">
                                <a href='/salon/mypage/show_reserve/{{ $reserve->id  }}'><h5>・{{ $reserve->date }}</h5></a>
                            </div>
                        @endforeach
                    </div>
                    
                    @else
                     <p>ご予約は入っておりません</p>
                    @endif
                    
                    
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
