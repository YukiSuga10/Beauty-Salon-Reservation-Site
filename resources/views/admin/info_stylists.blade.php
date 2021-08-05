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
                            <div style = "text-align: right"><a href="/admin/{{ $salon_id }}/{{$stylist->id}}/edit">編集する</a></div>
                            <hr>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
