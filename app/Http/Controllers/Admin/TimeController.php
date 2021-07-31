<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Reserve;
use App\Stylist;
use App\User;
use App\Menu;
use App\file_Image;
use App\Admin;
use App\time;
use DateTime;

class TimeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function config_time($id, Request $request){
        
        $time = new DateTime();
        $minStartTime = $time->setTime(9,00)->format('H:i:s');
        $maxEndTime = $time->setTime(20,00)->format('H:i:s');
        
        
        $startTime = $request['time']['startTime'];
        $endTime = $request['time']['endTime'];
        
        
        $times = time::insert([
            "admin_id" => $id,
            "startTime" => $startTime,
            "endTime" => $endTime
            ]);
        
        return redirect('/home')->with([
            "salon_id" => $id,
            'flash_message' => '設定が完了しました',
            ]);
        
        
    }
}
