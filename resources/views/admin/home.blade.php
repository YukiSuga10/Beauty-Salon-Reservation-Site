@extends('layouts.app_admin')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        @include('admin.sidemenu')
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$name}}管理画面</div>

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
                    
                    
                    <div class ='show_salon'>
                        <p><h5 style="border-bottom: 3px double #7C7B7B;">▶トップページ</h5></p>
                        
                            <div class="each_salon">
                                <a class="salonName" >{{ $admin->name }}</a><p class="region" style="color:#383333;">{{$admin->region}}</p>
                                
                                <hr class="division">
                                <a href="/admin/{{ $admin->id }}/salon_images" style="text-align:right;" class="edit">編集</a>
                                <div align="center" class="salonImage">
                                    @foreach ($images as $image)
                                        @if ($image->admin_id == $admin->id)
                                            <img src="{{ $image->path }}"　width="70" height = "120">
                                        @endif
                                    @endforeach
                                </div>
                                
                                <hr>
                                <div class="introduction">
                                <p style="margin-top:20px;">〜美容院から一言〜</p>
                                <label>{{ $admin->introduction }}</label>
                                </div>
              
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
