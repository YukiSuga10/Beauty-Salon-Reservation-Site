@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">レビュー</div>
                
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    
                    <h4 style="margin-bottom:20px">総合評価</h4>
                    <h5>全体平均：{{$average}}点</h5>
                    <p style="font-size:12px">※標準点を３点としています</p>
                    <p><このスタイリストの新着口コミ></p>
                    <hr>
                    @if (count($reviews) == 0)
                    現在レビューはありません
                    @else
                    @foreach ($reviews as $review)
                    
                            @foreach ($users as $user)
                                @if($review->user_id == $user->id)
                                    @yield('css')
                                    <h4 class = "user_name"><h7 style="font-size:15px">ユーザ名：</h7>{{$user->name}}さん</h4>
                                    <h5 class = "evaluation" style="margin-bottom:10px; ">総合評価：{{$review->evaluation}}点</h5>
                                    <p>〜コメント〜</p>
                                    <p class = "comment">{{$review->comment}}</p>
                                    @foreach ($menus as $menu)
                                        @if ($review->menu_id == $menu->id)
                                            <p class = "menu"><予約時のメニュー>：{{$menu->menu}}</p>
                                        @endif
                                    @endforeach
                                    <hr>
                                @endif
                            @endforeach
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

