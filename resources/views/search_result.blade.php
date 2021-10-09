@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-8" >
            
            <div class="card">
                <div class="card-header">{{$condition}}の検索結果</div>

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
                    
                    @if ($numbers == 0)
                    <p>申し訳ございません。お探しの美容院は見つかりませんでした。</p>
                    <p>条件を変えてお探しください</p>
                    <hr>
                    <div class = "back">
                        <input type="button" onclick="window.history.back();" value="戻る">
                    </div>
                    @else
                    <p>{{ $numbers }}件の結果がヒットしました</p>

                        <p><h5 style="border-bottom: 3px double #7C7B7B;">▶サロン一覧</h5></p>
                        <div class="each_salon">
                        @foreach($results as $result)
                            <p><a href = "/salon/{{$result->id}}" class="salonName">{{ $result->name }}</a></p>
                            <div align="center" class="salonImage">
                                    @foreach ($images as $image)
                                        @if ($image->admin_id == $result->id)
                                            <img src="{{ $image->path }}">
                                        @endif
                                    @endforeach
                                </div>
                                <hr>
                                <div class="introduction">
                                    <p style="margin-top:20px;">〜美容院から一言〜</p>
                                    <label>{{ $result->introduction }}</label>
                                    <div class="reserveBTN">
                                        <section>
                                          <a href="/salon/{{$result->id}}/reserve" class="reserveBtn">予約する</a>
                                        </section>
                                    </div>
                                </div>
                        @endforeach
                        </div>

                    <hr>
                    <div class = "back">
                        <input type="button" onclick="window.history.back();" value="戻る">
                    </div>
                    @endif
                    
                </div>
            </div>
        </div>
        @include('search.name_region')
    </div>
    <br>
    
</div>
@endsection
