@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">スタイリスト編集画面</div>
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
                        <img src='{{ $stylist->file_images->path }}' style = "width:50%">
                        <hr>
                        <form action = "/admin/{{ $stylist->id }}/edit" method="POST">
                            @csrf
                            @method('PUT')
                        <p>スタイリスト名：<input style="text" name = 'edit[name]' value="{{ $stylist->name }}" required></p>
                        <p>性別：
                        <select name = "edit[gender]" required>
                            @if ($stylist->gender == "男性")
                                <option value="男性" selected>男性</option>
                                <option value="女性">女性</option>
                            @else
                                <option value="男性">男性</option>
                                <option value="女性" selected>女性</option>
                            @endif
                        </select>
                        </p>
                        <p>(※写真を変更する場合は下記から選択してください)</p>
                        <p>写真：
                        <input type="file" name="edit[file]">
                        </p>
    
                        
                        <input type = "submit" value = "変更する">
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
