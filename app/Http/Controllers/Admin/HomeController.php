<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth\Admin;
use Illuminate\Http\Request;
use App\Reserve;
use App\Stylist;
use App\User;
use App\Menu;
use App\file_Image;
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
        return view('admin.home'); 
    }
    
    public function show_info()
    {
        $query = Stylist::query();
        $stylists = $query -> get();
        
        $stylist_images = file_Image::query()->get();

        
        return view('admin.info_stylists')->with([
            'stylists' => $stylists, 
            'stylist_images' => $stylist_images]);
    }
    
    public function show_calender(){
        return view('admin.show_calender');
    }
    
}
