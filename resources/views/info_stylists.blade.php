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
                    <form method ="POST" action='/salon/{{$salon_id}}/able_time'>
                                @csrf
                                <div style = "display:inline-flex">
                                    <input type = "date" name = "search[date]" value = "{{ old('search[date]') }}" required>
                                    <input type = 'submit' value = '検索する'>
                                </div>
                    <hr>
                        @foreach ($stylists as $stylist)
                                    <img src="{{ $stylist->file_images->path }}"　width="200px" height = "200px">
                            <p>スタイリスト名：{{ $stylist->name }}</p>
                            <p>性別：{{ $stylist->gender }}</p>
                            <p><a href = "/salon/{{ $salon_id }}/{{ $stylist->id }}/show_review">この美容師の口コミを見る</a></p>
                            @if(@count($stylist_times) == 0)
                            <hr>
                            @else
                                <p>〜{{$date}}日の空き状況〜</p>
                                    <select>
                                        @foreach($times as $time)
                                                @if ( @in_array($time,$stylist_times[$stylist->name]->toArray()))
                                                <option value = {{$time}}>{{@date("H:i",@strtotime($time))}}：×</option>
                                                @else
                                                <option value = {{$time}}>{{@date("H:i",@strtotime($time))}}：○</option>
                                                @endif
                                        @endforeach
                                    </select>
                                
                                <hr>
                            @endif
                        @endforeach
                    </form>
                    <div class = "back">
                        <input type="button" onclick="window.history.back();" value="戻る">
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection