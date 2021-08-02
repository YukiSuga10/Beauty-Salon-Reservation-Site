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

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('start','info_stylist','show_locationPage','show_salon','show_salonPage');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function show_salon(){
        $admins = Admin::query()->get();
        return view('first_launch')->with(["admins" => $admins]);
    }
    
    public function show_salonPage($id){
        $salon = Admin::find($id);
        return view('salonPage')->with(["salon" => $salon]);
    }
    
    
    public function start()
    {
        return view('first_launch');
    }
    
    public function launch_corporation()
    {
        return view('/corporation_views/launch_corporation');
    }
    
    
    public function info_stylist($id)
    {
        $stylists = Stylist::query()->where("admin_id",$id)->get();
        $stylist_times = null;
        return view("info_stylists")->with([
            'stylists' => $stylists, 
            'stylist_times' => $stylist_times,
            "salon_id" => $id
            ]);
    }
    
    
    
    public function mypage()
    {
        $user_id = Auth::id();
        $query = Reserve::query();
        $query -> where('user_id',$user_id)->orderBy('date', 'ASC');
        $reserves = $query->pluck('date');
        //まだ来店していない日にちの取得
        $newDate = [];
        foreach ($reserves as $reserve) {
            $dateNow = strtotime(date('Y-m-d'));
            $date = strtotime($reserve);
            
            if ($date >= $dateNow){
                $date = date('Y年m月d日',$date);
                array_push($newDate,$date);
            }else{
                continue;
            }
            
        }
        

        return view("mypage")->with(['newDate' => $newDate]);
    }
    
    
    
    
    
    public function edit_confirm(Request $request){
        $edit = $request['edit'];
        
        //日時と時間の表示方式変更
        $date = date('Y年m月d日',strtotime($edit["date"]));
        $time = date('G時i分',strtotime($edit["time"]));
        
        $menu = Menu::query()->where('id',$edit['menu'])->value('menu');
        $edit['stylist'] = Stylist::query()->where('id',$edit['stylist'])->value('name');
        
        if ($menu == "カット"){
            $time_required = "30分";
        }elseif ($menu == "カラー" || $menu == "パーマ"){
            $time_required = "1時間";
        }else{
            $time_required = "1時間30分";
        }
        
        $content = "変更確認";
        
        return view('confirm_reserve')->with([
            'edit' => $edit,
            'date' => $date,
            'time' => $time,
            'menu' => $menu,
            'time_require' => $time_required,
            'content' => $content]);
    }
    
    
    
    public function past_reserve($id){
        $past_reserves = Reserve::query()->where('user_id',$id)->where('date','<',date('Y-m-d H:i:s'))->orderBy('date', 'ASC')->get();
        
        foreach ($past_reserves as $past_reserve) {
            $past_reserve->date = date('Y年m月d日',strtotime($past_reserve->date));
        }

        return view('past_reserve')->with([
            'past_reserves' => $past_reserves
            ]);
    }
    
    
    public function show_reserve($reserve_id){
        //予約の取得
        $reserve = Reserve::find($reserve_id);
        //日付・時間の変換
        $reserve->startTime = date('G時i分',strtotime($reserve->startTime));
        $reserve->date = date('Y年m月d日',strtotime($reserve->date));
        
        //スタイリスト取得
        $stylist = Stylist::find($reserve->stylist_id);
        //所要時間
        if ($reserve['menu'] == "カット"){
            $time_required = "30分";
        }elseif ($reserve['menu'] == "カラー" || $reserve['menu'] == "パーマ"){
            $time_required = "1時間";
        }else{
            $time_required = "1時間30分";
        }
        
        //URL取得
        $url = url()->current();
        $key = parse_url($url);
        $path = explode("/", $key['path']);
        $last = end($path);

        return view('show_reserve')->with([
            "reserve" => $reserve,
             "stylist" => $stylist,
             "time_required" => $time_required,
             "last" => $last
            ]);
    }
    
    
    public function edit(Request $request){
        $reserve = $request['reserve'];
        
        //時間の表示形式変更
        $reserve['date'] = str_replace('日','',$reserve['date']);
        $reserve['date'] = str_replace('月','-',$reserve['date']);
        $reserve['date'] = str_replace('年','-',$reserve['date']);
        
        //スタイリストの取得
        $stylists = Stylist::query()->get();
        
        //時間の取得と表示形式変更
        $times = time::query()->pluck('time');
        foreach($times as $key => $time){
            $times[$key] = date('G時i分',strtotime($time));
        }
        
        
        return view('edit_date_stylist')->with([
            "reserve" => $reserve, 
            "stylists" => $stylists,
            "times" => $times]);
    }
    
    public function edit_time_menu(Request $request){
        $edit = $request['edit'];
        
        //時間の取得と表示形式変更
        $times = time::query()->pluck('time');
        foreach($times as $key => $time){
            $times[$key] = date('H:i',strtotime($time));
        }

        //時間の表示形式変更
        $edit['time'] = str_replace('時',':',$edit['time']);
        $edit['time'] = str_replace('分',':',$edit['time']);
        $s = '00';
        $edit['time'] = $edit['time'].$s;
        $edit['time'] = date('H:i',strtotime($edit['time']));
        
        //全メニューの取得
        $menus = Menu::query()->get();
        
        //選択された日付と美容師の予約の入っている時間
        $not_abletime = Reserve::query()->where('stylist_id',$edit['stylist'])->where('date',$edit['date'])->pluck('startTime');
        
        //予約不可の時間の表示形式変更
        $not_times = [];
        foreach ($not_abletime as $time){
            $time = date('H:i',strtotime($time));
            array_push($not_times,$time);
        }
        
        $not_times = array_diff($not_times,array($edit['time']));
        $not_times = array_values($not_times);
        
        //予約可能時間の取得
        if (count($not_times) == 0 ){
            //時間の取得
            $possible_time = $times;
        }else{
            //時間の取得
            $possible_time = [];
            foreach ($times as $time){
                if (!(in_array($time,$not_times))){
                    array_push($possible_time,$time);
                }
            }
        }
        
        
        return view('edit_time_menu')->with([
            "edit" => $edit, 
            "menus" => $menus,
            "possible_times" => $possible_time]);;
    }
    
    
    public function update(Request $request, Reserve $reserve){
        $update = $request['edit'];
        dd($update);
        $reserve->fill($update)->save();
        return redirect('/mypage');
    }
    
    public function delete(Request $request){
        $input = $request['reserve'];

        //ユーザID取得
        $user_id = Auth::id();
        
        //スタイリストID取得
        $query_stylist = Stylist::query();
        $query_stylist -> where('name',$input['stylist']);
        $stylist_id = $query_stylist->value('id');
        
        //日付の表示形式変更
        $Redate = str_replace('日','',$input['date']);
        $Redate = str_replace('月','-',$Redate);
        $Redate = str_replace('年','-',$Redate);
        
        //時間の表示形式変更
        $reTime = str_replace('時',':',$input['time']);
        $reTime = str_replace('分',':',$reTime);
        $s = '00';
        $reTime = $reTime.$s;
        
        //メニューIDの取得
        $query_menu = Menu::query();
        $query_menu -> where('menu',$input['menu']);
        $menu_id = $query_menu->value('id');
        
        
        $query = Reserve::query();
        $query -> where('user_id',$user_id);
        $query -> where('stylist_id',$stylist_id);
        $query -> where('menu_id',$menu_id);
        $query -> where('date',$Redate);
        $query -> where('startTime',$reTime);
        $query -> delete();
        
        return redirect('mypage');
    }
    
    public function show_locationPage()
    {
        return view('location');
    }
    
}
