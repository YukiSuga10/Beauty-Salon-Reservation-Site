@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">スタイリスト一覧</div>

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
                    <p>空き状況検索はこちらから</p>
                    <form method ="POST" action='/able_time'>
                                @csrf
                                <div style = "display:inline-flex">
                                    <input type = "date" name = "search[date]" value = "{{ old('search[date]') }}" required>
                                    <input type = 'submit' value = '検索する'>
                                </div>
                    <hr>
                        @foreach ($stylists as $stylist)
                            @foreach ($stylist_images as $stylist_image)
                                @if ($stylist->id == $stylist_image->id)
                                    <img src="{{ $stylist_image->path }}"　width="200px" height = "200px">
                                @else
                                    <p></p>
                                @endif 
                        @endforeach

                            <p>スタイリスト名：{{ $stylist->name }}</p>
                            <p>性別：{{ $stylist->gender }}</p>
                            <p><a href = "/{{ $stylist->id }}/show_review">この美容師の口コミを見る</a></p>
                            
                            @if($stylist_times == null)
                            <hr>
                            @else
                                @foreach ($stylist_times as $key => $stylist_time)
                                        @if ($stylist->id == $key)
                                            <p>〜{{$date}}日の空き状況〜</p>
                                            <select>
                                                @foreach ($stylist_time as $time => $correction)
                                                    <option value = {{$time}}>{{$time}}：{{$correction}}</option>
                                                @endforeach
                                            </select>
                                            <hr>
                                        @else
                                            @continue
                                        @endif
                                @endforeach
                            @endif
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
