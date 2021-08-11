<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Stylist;
use App\file_Image;
use App\Menu;
use App\Admin;
use App\time;

class HomeController extends Controller
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
    
    public function index()
    {
        $salon_id = Auth::guard('admin')->user()->id;
        $salon_name = Admin::query()->where("id",$salon_id)->value('name');
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
    
    public function register_salonImage($id){
        return view('admin.register_salonImage')->with([
            "id" => $id
            ]);
    }
    
    public function show_configAccess($id){
        $salon = Admin::query()->where("id",$id)->first();
        $setAccess = $salon->address;
        return view("admin.config_access")->with([
            "salon" => $salon,
            "address" => $setAccess,
            "id" => $id,
            ]);
    }
    
    public function show_menuPage($id){
        $selected_menu = Menu::query()->where("admin_id",$id)->first();
        return view('admin.config_menu')->with([
            "id" => $id,
            "selected_menu" => $selected_menu]);
    }
    
    public function show_timePage($id){
        $setTime = time::query()->where("admin_id",$id)->first();
        return view('admin.config_time')->with([
            "id" => $id,
            "setTime" => $setTime,]);
    }
    
    public function edit($salon_id,$stylist_id){
        $stylist = Stylist::query()->where("id",$stylist_id)->first();
        return view("admin.edit_stylist")->with(["stylist" => $stylist]);
    }
    
    public function update_stylist($id,Request $request){
        $edit_content = $request['edit'];
        
        $stylist = Stylist::query()->where("id",$id)->first();
        $stylist->name = $edit_content['name'];
        $stylist->gender = $edit_content['gender'];
        if ($edit_content['file'] == null){
            $stylist->file_images->path;
        }else{
            $stylist->file_images->path = $edit_content['file'];
        }
        $stylist->update();
        
        return redirect("/admin/".$stylist->admin->id."/info_stylist")->with(['flash_message' => "変更しました"]);;
        
    }
    
    public function delete($salon_id,$stylist_id){
        $stylist = Stylist::query()->where("id",$stylist_id)->first();
        $stylist->delete();
        return redirect("/admin/".$salon_id."/info_stylist")->with(['flash_message' => "完了しました"]);
        
    }
    
}
