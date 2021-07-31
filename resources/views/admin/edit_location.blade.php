@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アクセスの編集</div>

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
                    <div id="map" style="height:500px">
            	   <form action = "edit_location" method = "POST">
            	       @csrf
            	       <p>1：住所を入力してください</p>
            	       <input name = "edit[address]" type = "text" id = "address" placeholder = "住所" style="width:500px">
            	       <hr>
            	       <p>2：駅からのアクセスを入力してください</p>
            	       <p>最寄駅：<input name = "edit[station]" class = "station" type = "text" style = "text-align: right; width:100px; height:20px ">駅</p>
            	       <p>最寄駅から徒歩：<input name = "edit[time]" class = "station" type = "text" style = "text-align: right; width:50px; height:20px ">分</p>
            	       
            	   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
