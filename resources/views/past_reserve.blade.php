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
                    <p>〜過去利用履歴〜</p>
                   <hr>
                    @if (@count($past_reserves) != 0)
                    <div class = "reserves">
                        @foreach ($past_reserves as $past_reserve)
                            <div class = "reserve">
                                <a href='/salon/show_reserve/{{ $past_reserve->id  }}/past'><h5 >・{{ $past_reserve->date }}</h5></a>
                            </div>
                        @endforeach
                    </div>
                    @else
                    <p>過去のご利用はございません</p>
                    @endif
                    
                    
            
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
