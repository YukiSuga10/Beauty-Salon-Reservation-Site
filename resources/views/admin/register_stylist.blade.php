@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">スタイリスト登録</div>
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
                    <form action="/upload/image" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class = 'stylist_name'>
                            <p>1:名前</p>
                            <input type = "text" name = 'stylist[name]' placeholder = "名前" required>
                        </div>
                        <p></p>
                        <div class = 'stylist_gender'>
                            <p>2:性別</p>
                            <select name = "stylist[gender]">
                                <option hidden>選択してください</option>
                                <option value="男性">男性</option>
                                <option value="女性">女性</option>
                            </select>
                            
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <p></p>
                        <div class = 'stylist_image'>
                            <p for="photo">3:写真</p>
                            <input type="file" name="file">
                        </div>
                        <p></p>
                        <input type = "submit" value = "登録">
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
