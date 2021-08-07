@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">アクセスの設定</div>
                <div class="card-body">
                    

                
                    
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    美容院の住所を設定してください
                    @if (session('flash_message'))
                            <div class="flash_message">
                                {{ session('flash_message') }}
                            </div>
                    @endif
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    <hr>
                    @if( $address == null )
                        <form action = "/admin/{{$id}}/set_address" method = "POST">
                            @csrf
                            <div>
                            郵便番号:
                            <input type="text" name="郵便番号" onKeyUp="AjaxZip3.zip2addr(this,'','住所','住所');">
                            </div>
                            <br>
                            住所:
                            <input type="text" name="住所" size="40" id = "address" required>
                            <hr>
                            <input type="submit" value = "登録">
                        </form>
                    @else
                        <form action = "/admin/{{$id}}/set_address" method = "POST">
                            @csrf
                            <div>
                            郵便番号:
                            <input type="text" name="郵便番号" onKeyUp="AjaxZip3.zip2addr(this,'','住所','住所');">
                            </div>
                            <br>
                            住所:
                            <input type="text" name="住所" size="40" id = "address" value= "{{ $address }}" required>
                            <hr>
                            <input type="submit" value = "変更">
                        </form>
                    @endif
                    
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
