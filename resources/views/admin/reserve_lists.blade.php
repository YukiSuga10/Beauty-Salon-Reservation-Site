@extends('layouts.app_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">予約一覧</div>
                
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        
                    <main class="mt-4">
                        @yield('content')
                    </main>
                    <h5 class="title">{{$date}}</h5>
                    <p class="info">○＝空き</p>
                    <div class = 'confirm_calender'>
                        <table border="1" class="reserve_list" align="center" >
                             <tr>   
                                    <th>   </th>
                                    
                            @foreach($stylists as $stylist)
                                    <th>{{$stylist->name}}</th>
                                    
                            @endforeach
                            
                                    @foreach($stylists_appoint as $key => $reserves)
                                        <tr>
                                            <td>{{$key}}</td>
                                        @foreach ($reserves as $name => $value)
                                        @if ($value == "×")
                                                <td>○</td>
                                        @else
                                            @foreach($value as $key => $reserve)
                                                <td>
                                                    <a href="/admin/reserve/{{$reserve->id}}">予約あり</a>
                                                </td>
                                            @endforeach
                                        @endif

                                        @endforeach
                                        </tr>
                            
                                    @endforeach
                            </tr>
           
             
                           
                            
                        </table>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

