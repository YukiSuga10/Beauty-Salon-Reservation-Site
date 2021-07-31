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

class HomeController extends Controller
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
    
    public function index()
    {
        $salon_id = Auth::guard('admin')->user()->id;
        $salon_name = Admin::find($salon_id)->value('name');
        return view('admin.home')->with([
            "salon_id" => $salon_id,
            "name" => $salon_name]);
    }
    
    public function show_info($id)
    {
        $stylists = Stylist::query()->where("admin_id",$id)->get();
        
        return view('admin.info_stylists')->with([
            'stylists' => $stylists,
            "salon_id" => $id
            ]);
    }
    
    public function show_calender(){
        return view('admin.show_calender');
    }
    
    public function admin_access(){
        return view('admin.admin_location');
    }
    
    public function edit_accessForm(){
        return view('admin.edit_location');
    }
    
    public function show_menuPage($id){
        return view('admin.config_menu')->with(["id" => $id]);
    }
    
    public function show_timePage($id){
        return view('admin.config_time')->with(["id" => $id]);
    }
}
