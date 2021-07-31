<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Menu;


class MenuController extends Controller
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
    
    
    public function config_menu($id, Request $request){
        $config_menus = $request->input('menu');
        $content = array_fill(0,5,0);
        foreach ($config_menus as $menu){
            if ($menu == 0){
                $content[$menu] = 1;
            }elseif($menu == 1){
                $content[$menu] = 1;
            }elseif($menu == 2){
                $content[$menu] = 1;
            }elseif($menu == 3){
                $content[$menu] = 1;
            }elseif($menu == 4){
                $content[$menu] = 1;
            }
        }
        
        Menu::updateOrCreate(
            ["admin_id" => $id],
            ["admin_id" => $id,
            "cut" => $content[0],
            "color" => $content[1],
            "perm" => $content[2],
            "cut・color" => $content[3],
            "cut・perm" => $content[4]]
            );
        
        return redirect('/home')->with([
            "salon_id" => $id,
            'flash_message' => '設定が完了しました']);
    }
}
