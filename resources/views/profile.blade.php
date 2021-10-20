@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">プロフィール</div>

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
                    <div class="profile">
                        <form action="/salon/profile/edit" method="POST">
                            @csrf
                            @method('PUT')
                            <p>名前：</p><input class="dynamic-line-center" type="text" name = "edit[name]" value="{{ $user->name }}"><hr>
                            <div class ="underline-center"></div>
                            <p>性別：</p>
                            <select name = "edit[gender]">
                                @if ($user->gender == "男性")
                                    <option hidden>選択してください</option>
                                    <option value="男性" selected>男性</option>
                                    <option value="女性">女性</option>
                                @else
                                    <option hidden>選択してください</option>
                                    <option value="男性">男性</option>
                                    <option value="女性" selected>女性</option>
                                @endif
                            </select><hr>
                            <p>メールアドレス：</p><input class="dynamic-line-center" name = "edit[email]" type="text" value="{{ $user->email }}"><hr>
                            <input type="submit" value="変更する">
                        </form>
                    </div>
                    
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
