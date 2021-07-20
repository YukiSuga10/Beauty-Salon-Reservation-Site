<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Reserve;
use App\Stylist;
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
        $this->middleware('auth')->except('start','info_stylist','show_locationPage');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    
    public function start()
    {
        return view('launch');
    }
    
    public function launch_corporation()
    {
        return view('/corporation_views/launch_corporation');
    }
    
    
    public function info_stylist()
    {
        $query = Stylist::query();
        $stylists = $query -> get();
        $stylist_times = null;
        $stylists_id = Stylist::query()->pluck('id');
        $stylist_times = null;
        $allID = $stylists->pluck('id');
        
        //現在ログイン中のユーザIDを変数$user_idに格納する
        $user_id = Auth::id();
        //imagesテーブルからuser_idカラムが変数$user_idと一致するレコード情報を取得し変数$user_imagesに格納する
        $stylist_images = file_Image::query()->get();

        
        return view("info_stylists")->with([
            'stylists' => $stylists, 
            'stylist_times' => $stylist_times, 
            'stylists_id' => $stylists_id,
            'allID' => $allID,
            'stylist_times' => $stylist_times,
            'stylist_images' => $stylist_images]);
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
    
    
    
    public function confirm(Request $request)
    {
        $input = $request['reserve'];
        
        //日時と時間の表示方式変更
        $date = date('Y年m月d日',strtotime($input["date"]));
        $time = date('G時i分',strtotime($input["time"]));

        $query = Menu::query();
        $query -> where('id',$input["menu"]);
        $menu = $query->value("menu");
        $reserve = $input;
        //所要時間
        if ($menu == "カット"){
            $time_required = "30分";
        }elseif ($menu == "カラー" || $menu == "パーマ"){
            $time_required = "1時間";
        }else{
            $time_required = "1時間30分";
        }
        
        return view('confirm_reserve')->with([
            'reserve' => $reserve,
            'date' => $date,
            'time' => $time,
            'menu' => $menu,
            'time_require' => $time_required,]);
    }
    
    
    
    public function past_reserve(){
        $user_id = Auth::id();
        $query = Reserve::query();
        $query -> where('user_id',$user_id)->orderBy('date', 'ASC');
        $reserves = $query->pluck('date');
        
        $pastDate = [];
        //過去の来店日時の取得
        foreach ($reserves as $reserve) {
            $dateNow = strtotime(date('Y-m-d'));
            $date = strtotime($reserve);
    
            if ($date < $dateNow){
                $date = date('Y年m月d日',$date);
                array_push($pastDate,$date);
            }else{
                continue;
            }
        }
        return view('past_reserve')->with(['pastDate' => $pastDate]);
    }
    
    
    public function show_reserve($date){
        //date型の変換
        $Redate = str_replace('日','',$date);
        $Redate = str_replace('月','-',$Redate);
        $Redate = str_replace('年','-',$Redate);
        
        //ユーザの指定日時の予約詳細取得
        $user_id = Auth::id();
        $query = Reserve::query();
        $query -> where('user_id',$user_id);
        $query -> where('date',$Redate);
        $reserves = $query->get();
       
        //時間の取得・変換
        $time = $query->value('startTime');
        $time = date('G時i分',strtotime($time));

        //スタイリスト取得
        $stylist_id = $query->value('stylist_id');
        $query2 = Stylist::query();
        $query2 -> where('id',$stylist_id);
        $stylist = $query2->value('name');
        
        //メニュー取得
        $menu_id = $query->value('menu_id');
        $query3 = Menu::query();
        $query3 -> where('id',$menu_id);
        $menu = $query3->value("menu");

        //所要時間
        if ($menu == "カット"){
            $time_required = "30分";
        }elseif ($menu == "カラー" || $menu == "パーマ"){
            $time_required = "1時間";
        }else{
            $time_required = "1時間30分";
        }
        
        //URL取得
        $url = url()->previous();
        $key = parse_url($url);
        $path = explode("/", $key['path']);
        $last = end($path);

        return view('show_reserve')->with([
            'date' => $date,
            'time' => $time,
            'stylist' => $stylist,
            'time_required' => $time_required,
            'menu' => $menu,
            'last' => $last]);
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
    
    
    public function update(){
        
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
