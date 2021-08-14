<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Reserve;
use App\Stylist;
use App\Admin;
use App\User;
use App\Menu;
use App\time;
use App\file_Image;
use DateTime;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('result_salon','result_region');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
     public function result_salon(Request $request){
        $search = $request['search'];
        $searchResult = Admin::query()->where("name",'LIKE', "%{$search['salonName']}%")->get();
        
        $result_num = count($searchResult);
        
        return view('search_result')->with([
            "results" => $searchResult,
            "numbers" => $result_num,
            "condition" => $search['salonName']]);
     }
     
     public function result_region(Request $request){
         $search = $request->input('search');
         
         $salons = Admin::query()->where("region",$search[0])->get();
         
         $result_num = count($salons);
         
         return view('search_result')->with([
             "results" => $salons,
             "numbers" => $result_num,
             "condition" => $search[0]
            ]);
         
     }
    
    
}
