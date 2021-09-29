@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">予約カレンダー</div>
                
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    <h5 class="title">
                        @if ($content == "pre")
                        {{ $calendar->getTitle_pre() }}
                        @elseif ($content == "next")
                        {{ $calendar->getTitle_next() }}
                        @else
                        {{ $calendar->getTitle() }}
                        @endif
                    </h5>
                    <div class="box">
                        <a href="/admin/{{ $id }}/pre/{{ $pre_month }}" class="pre"><i class="fas fa-arrow-left"></i> 前の月へ</a>
                        <a href="/admin/{{ $id }}/next/{{ $next_month }}" class="next">次の月へ <i class="fas fa-arrow-right"></i></a>
                    </div>
                    
                    
                    {!! $calendar->render() !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

