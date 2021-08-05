@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">メニューの設定</div>
                <div class="card-body">
                    

                
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    美容院の営業時間を設定してください
                    (※9:00〜20：00までの間で設定してください)
                    @if (session('flash_message'))
                            <div class="flash_message">
                                {{ session('flash_message') }}
                            </div>
                    @endif
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    <hr>
                    <form action="/admin/{{$id}}/config_time" method="POST" onSubmit="return checkTime(this)">
                        @csrf
                        @if (@count($setTime) != 0)
                        <p><input type="time" id = "startTime" name="time[startTime]" value = "{{ $setTime->startTime }}" required>　〜　<input type="time" id = "endTime" name="time[endTime]" value = "{{ $setTime->endTime }}" required></p>
                        <br>
                        <input type = "submit" value = "変更">
                        @else
                        <p><input type="time" id = "startTime" name="time[startTime]" required>　〜　<input type="time" id = "endTime" name="time[endTime]" required></p>
                        <br>
                        <input type = "submit" value = "登録">
                        @endif
                    </form>
                    <script src="{{ asset('/js/result.js') }}"></script>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
