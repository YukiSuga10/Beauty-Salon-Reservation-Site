@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">メニューの設定</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    美容院でご利用可能なメニューを以下から選択してください
                    @if (session('flash_message'))
                            <div class="flash_message">
                                {{ session('flash_message') }}
                            </div>
                    @endif
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    <hr>
                    @if(isset($selected_menu))
                    <form action="/admin/{{$id}}/config_menu" method="POST">
                        @csrf
                        <p>[メニュー]</p>
                        @if ($selected_menu->cut == 1)
                        <input type = "checkbox" checked="checked" name = "menu[]" value = "0">カット<br>
                        @else
                        <input type = "checkbox" name = "menu[]" value = "0">カット<br>
                        @endif
                        @if ($selected_menu->color == 1)
                        <input type = "checkbox" checked="checked" name = "menu[]" value = "1">カラー<br>
                        @else
                        <input type = "checkbox" name = "menu[]" value = "1">カラー<br>
                        @endif
                        @if ($selected_menu->perm == 1)
                        <input type = "checkbox" checked="checked" name = "menu[]" value = "2">パーマ<br>
                        @else
                        <input type = "checkbox" name = "menu[]" value = "2">パーマ<br>
                        @endif
                        @if ($selected_menu->cut・color == 1)
                        <input type = "checkbox" checked="checked" name = "menu[]" value = "3" >カット・カラー<br>
                        @else
                        <input type = "checkbox" name = "menu[]" value = "3">カット・カラー<br>
                        @endif
                        @if ($selected_menu->cut・perm == 1)
                        <input type = "checkbox" checked="checked" name = "menu[]" value = "4" >カット・パーマ<br>
                        @else
                        <input type = "checkbox" name = "menu[]" value = "4">カット・パーマ<br>
                        @endif
                        <p></p>
                        <input type = "submit" value = "変更">
                    </form>
                    @else
                     <form action="/admin/{{$id}}/config_menu" method="POST">
                        @csrf
                        <p>[メニュー]</p>
                        <input type = "checkbox" name = "menu[]" value = "0">カット<br>
                        <input type = "checkbox" name = "menu[]" value = "1">カラー<br>
                        <input type = "checkbox" name = "menu[]" value = "2">パーマ<br>
                        <input type = "checkbox" name = "menu[]" value = "3">カット・カラー<br>
                        <input type = "checkbox" name = "menu[]" value = "4">カット・パーマ<br>
                        <p></p>
                        <input type = "submit" value = "登録">
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
