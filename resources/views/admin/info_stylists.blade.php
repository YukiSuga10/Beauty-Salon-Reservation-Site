@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">スタイリスト一覧<div style = "text-align: right"><a href="/admin/{{ $salon_id }}/register_stylist">新規登録する</a></div></div>
                
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
                            <hr>
                    @endif

                    <main class="mt-4">
                        @yield('content')
                    </main>
                        @foreach ($stylists as $stylist)
                            <img src='{{ $stylist->file_images->path }}' style = "width:50%">
                            <br>
                            <p>スタイリスト名：{{ $stylist->name }}</p>
                            <p>性別：{{ $stylist->gender }}</p>
                            <p><a href = "/{{ $stylist->id }}/show_review">この美容師の口コミを見る</a></p>
                            <div style = "text-align: right">
                                <div style="display:inline-flex">
                                <form method="POST" name="a_form" action="/admin/{{ $salon_id }}/{{$stylist->id}}/delete">
                                   @csrf
                                   @method('delete')
                                  <input type = "submit" id = "stylist" style = "border-radius: 30px;" onclick = "return deleteStylist(this);" value = "削除する">
                                </form>
                                <form method="GET"  action = "/admin/{{ $salon_id }}/{{$stylist->id}}/edit">
                                    @csrf
                                    <input type = "submit" style = "border-radius: 30px;" value = "編集する">
                                </form>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
