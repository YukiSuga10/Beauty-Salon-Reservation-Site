@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color:#fff1e0;">レビュー</div>
                <div id="nav">
                    <ul>
                        <li><a href = '/salon/{{$salon->id}}'><i class="fas fa-home"></i> ホーム</a></li>
                        <li><a href = '/salon/{{$salon->id}}/info_stylist'><i class="fas fa-cut"></i> スタイリスト</a></li>
                        <li class="current"><a href = '/salon/{{$salon->id}}/salon_review'><i class="fas fa-comment-dots"></i> 口コミ</a></li>
                        <li><a href = '/salon/{{$salon->id}}/show_location'><i class="fas fa-map-marker-alt"></i> アクセス</a></li>
                    </ul>
                </div>
                
                
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    <div class="salon_evaluation">
                        <h3>総合評価</h3>
                        <h4>全体平均：<label class="average">{{$average}}点</label></h4>
                        <p>※標準点を３点としています</p>
                    </div>
                    
                    <form action="/salon/{{ $salon->id }}/salon_review" method="POST">
                        @csrf
                        <div class="search_review" style="background-color:#eeeeee;">
                            <label style="color:#7f7f7f;">絞り込み</label>：
                            <div class="search_menu">
                                <p>メニュー：
                                <select name = "refine[menu]"style="width:200px;" >
                                    <option value="all">全て</option>    
                                    <option value="カット" @if($refine["menu"] == "カット") selected @endif>カット</option>    
                                    <option value="カラー" @if($refine["menu"] == "カラー") selected @endif>カラー</option>    
                                    <option value="パーマ" @if($refine["menu"] == "パーマ") selected @endif>パーマ</option>
                                    <option value="カラー・カラー" @if($refine["menu"] == "カット・カラー") selected @endif>カット・カラー</option>
                                    <option value="カラー・パーマ"　@if($refine["menu"] == "カット・パーマ") selected @endif>カット・パーマ</option>
                                </select>
                                </p>
                            </div>   
                            <br>
                            <div class="search_evaluation">
                                <p>点数：
                                <select name = "refine[evaluation]" style="width:200px;">
                                    <option value="all">全て</option>    
                                    <option value="5" @if($refine["evaluation"] == "5") selected @endif>5点</option>    
                                    <option value="4" @if($refine["evaluation"] == "4") selected @endif>4点</option>    
                                    <option value="3" @if($refine["evaluation"] == "3") selected @endif>3点</option>  
                                    <option value="2" @if($refine["evaluation"] == "2") selected @endif>2点</option>    
                                    <option value="1" @if($refine["evaluation"] == "1") selected @endif>1点</option>    
                                </select>
                                </p>
                            </div>
                            <input class ="button" type="submit" value="絞り込む">
                        </div>
                    </form>
                    
                    <hr style="margin-top:5px;">
                    <p><{{$salon->name}}の新着口コミ></p>
                    @if (count($reviews) == 0)
                    現在レビューはありません
                    <hr>
                    @else
                    @foreach ($reviews as $review)
                            @foreach ($users as $user)
                                @if($review->reserve->user_id == $user->id)
                                <div class ='each_review'>
                                    <h5 class = "review_userName" style="font-size:15px"><i class="fas fa-user"></i> ユーザ名：{{$user->name}}さん</h5>
                                    <h5 style="margin-bottom:10px; ">総合評価：<label class="evaluation">{{$review->evaluation}}点</label></h5>
                                    <p>〜コメント〜</p>
                                    <p class = "comment">{{$review->comment}}</p>
                                    <p class = "menu"><予約時のメニュー>：{{$review->reserve->menu}}</p>
                                </div> 
                                
                                @endif
                            @endforeach
                    @endforeach
                    @endif
                    <div class = "back">
                        <input type="button" onclick="window.history.back();" value="戻る">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

