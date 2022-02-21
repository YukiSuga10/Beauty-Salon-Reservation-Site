@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="row justify-content-center">
        <div class="col-md-9" >
            
            <div class="card">
                <div class="card-header">ようこそ！！</div>

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
                   
                    @if ($count_salon == 0)
                        <div class ='show_salon'>
                            美容院が登録されていません
                        </div>
                    @else
                        <div class ='show_salon'>
                            <p><h5 style="border-bottom: 3px double #7C7B7B;">▶サロン一覧</h5></p>
                            @foreach($admins as $admin)
                                <div class="each_salon">
                                    <a class="salonName" href = "/salon/{{$admin->id}}">{{ $admin->name }}</a><p class="region" style="color:#383333;">{{$admin->region}}</p>
                                    
                                    <hr class="division">
                                    <div align="center" class="salonImage">
                                        @if ($count == 0)
                                            写真は登録されていません
                                        @else
                                            @foreach ($images as $key => $image)
                                                @if ($key== $admin->id)
                                                    @foreach ($image as $path)
                                                        <img src="{{ $path }}"　width="70" height = "120">
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                        
                                    </div>
                                    <hr>
                                    <div class="introduction">
                                        <p style="margin-top:20px;">〜美容院から一言〜</p>
                                        <label>{{ $admin->introduction }}</label>
                                        <div class="reserveBTN">
                                            <section>
                                              <a href="/salon/{{$admin->id}}/reserve" class="reserveBtn">予約する</a>
                                            </section>
                                        </div>
                                    </div>
                            
                        </div>
                            @endforeach       
                        <div class="d-flex justify-content-center">
                            {{$admins->links()}}
                        </div>        
                    @endif
                    
                </div>
                    <br>
                    <div>
                        <p><h5 style="border-bottom: 3px double ;">▶予約確認</h5></p>
                        <a href = "/salon/mypage">予約確認はこちらから</a>
                    </div>
                    <br>
                
                </div>
            </div>
        </div>
        
            @include('search.name_region_ranking')
            
    </div>
    
    <br>
    
    
</div>
@endsection
