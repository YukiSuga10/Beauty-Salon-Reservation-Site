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

class GmapsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function set_address($id, Request $request){
        $admin = Admin::query()->where("id",$id)->first();
        
        $admin->address = $request['住所'];
        $admin->save();
    
        
        return redirect('admin/home')->with([
            "salon_id" => $id,
            'flash_message' => '設定が完了しました']);
        
    }
    
    
    
    
}
