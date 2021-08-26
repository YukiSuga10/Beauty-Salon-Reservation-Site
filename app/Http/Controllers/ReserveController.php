<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Reserve;
use App\Stylist;
use App\stylist_cut;
use App\stylist_color;
use App\stylist_perm;
use App\stylist_cut_and_color;
use App\stylist_cut_and_perm;
use App\User;
use App\Admin;
use App\Menu;
use App\time;
use DateTime;

class ReserveController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function reserve_date_stylist($id)
    {
        //スタイリストの取得
        $stylists = Stylist::query()->where("admin_id",$id)->get();
        
        //美容院の取得
        $salon = Admin::query()->where("id",$id)->first();

        return view('reserve_date_stylist')->with([
            "stylists" => $stylists,
            "salon_id" => $id,
            "salon" => $salon
            ]);
    }
    
    
    public function reserve_time_menu($id, Request $request)
    {
        
        $input = $request['reserve'];
        
        $salon = Admin::find($id)->first();

        //営業時間の取得
        $startTime = time::query()->where("admin_id",$id)->value('startTime');
        $endTime = time::query()->where("admin_id",$id)->value('endTime');
        
        $diff = strtotime($endTime)-strtotime($startTime);
        $count = $diff/1800;
        
        $times = [];
        array_push($times,date('H:i',strtotime($startTime)));
        for ($i = 1;$i<= $count; $i++){
            $startTime = strtotime('+30 minutes', strtotime($startTime));
            $startTime = date('H:i',$startTime);
            array_push($times,$startTime);
        }
        
        //メニューの取得
        $salon_menu = [];
            if ($salon->menus->first()->cut == 1){
                array_push($salon_menu,"カット");
            }
            if ($salon->menus->first()->color == 1){
                array_push($salon_menu,"カラー");
            }
            if ($salon->menus->first()->perm == 1){
                array_push($salon_menu,"パーマ");
            }
            if ($salon->menus->first()->cut・color == 1){
                array_push($salon_menu,"カット・カラー");
            }
            if ($salon->menus->first()->cut・perm == 1){
                array_push($salon_menu,"カット・パーマ");
            }
        
        
        //日付の表示形式変更
        $date = $input['date'];
        $date = date('Y年m月d日',strtotime($input["date"]));
        
        //スタイリストIDの取得
        $stylist_id = $salon->stylists->first()->id;
        
        //予約できない時間の取得
        $notPossible_time = $salon->reserves->where('date',$input['date'])->where('stylist_id',$stylist_id)->pluck('startTime');

        if (count($notPossible_time) == 0 ){
            //時間の取得
            $possible_time = $times;
        }else{
            //時間の取得
            $possible_time = [];
            foreach ($times as $time){
                if (!(in_array($time,$notPossible_time->toArray()))){
                    array_push($possible_time,$time);
                }
            }
            
        }
        
        return view('reserve_time_menu')->with([
            "times" => $times,
            "menus" => $salon_menu,
            "reserve" => $input,
            "salon_id" => $id,

            ]);
    }
    
    public function confirm($id,Request $request)
    {
        $salon = Admin::query()->where("id",$id)->first();
        
        $content = "予約確認";
        $reserve = $request['reserve'];
        $date = date('Y年m月d日',strtotime($reserve["date"]));
        $time = date('G時i分',strtotime($reserve["time"]));
        $menu = $reserve["menu"];
        
        
        
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
            "salon" => $salon,
            'date' => $date,
            'time' => $time,
            'time_require' => $time_required,
            'content' => $content,
            "salon_id" => $id]);
    }
    
    public function reserve($id,Request $request){
        $input = $request['reserve'];
        $salon = Admin::query()->where("id",$id)->first();
        
        //入力された日にちが過去の場合
        $date = strtotime($input['date']);
        $dateNow = strtotime(date('Y-m-d'));
        
        if ($date < $dateNow){
            $date_correction = false;
            return view('failure_reserve')->with(["date_correction" => $date_correction]);
        }else{
            
        //データベースのレコード数取得
        $reserved = Reserve::query()->get();
        $num_rows = count($reserved);
        
        $stylist_id = $salon->stylists->where('name',$input["stylist"])->first()->id;
        
        if ($num_rows > 0){
            
            $user_id = Auth::id();
            
            if ($input['menu'] == "カット"){
                $cut_reserved = Reserve::query()->where("menu","カット")->get();
                $num_rows = count($cut_reserved);
                
                //同じスタイリストの同時刻のカラー取得
                $color_time = stylist_color::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                
                //同じスタイリストの同時刻のパーマ取得
                $perm_time = stylist_perm::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                
                //同じスタイリストの同時刻のカット・カラー取得
                $cut_and_color_time = stylist_cut_and_color::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                
                //同じスタイリストの同時刻のカット・パーマ取得
                $cut_and_perm_time = stylist_cut_and_perm::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                
                //レコードの数で条件分岐
                
                if ($num_rows == 0){
                        $reserve= Reserve::insert([
                            "admin_id" => $id,
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                          ]);
                          
                        $stylist_cut = stylist_cut::insert([
                            "admin_id" => $id,
                            "stylist_id" => $stylist_id,
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                        ]);
                        
                        return redirect('/reserved')->withInput($input);
                    
                }else{
                    //inputをTIME型に変換
                    $start_time = strtotime($input["time"]);
                    $start_time = date("H:i:s",$start_time);
                    
                    $lists = stylist_cut::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value('stylist_id');
                    
                    if ($lists == null && $color_time != $start_time && $perm_time != $start_time && $cut_and_color_time != $start_time && $cut_and_perm_time != $start_time){
                        //予約テーブルへのDB書き込み
                            $reserve= Reserve::insert([
                            "admin_id" => $id,
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                          ]);
                              
                         //stylist_cutテーブルへのDB書き込み
                            $stylist_cut = stylist_cut::insert([
                            "admin_id" => $id,
                            "stylist_id" => $stylist_id,
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                        
                        return redirect('/reserved')->withInput($input);
                    }else{
                        $date_correction = true;
                        return view('failure_reserve')->with(["date_correction" => $date_correction]);
                    }
                }
            }elseif ($input['menu'] == "カラー"){
                    //データベースの接続
                    $color_reserved = Reserve::query()->where("menu","カラー")->get();
                    $num_rows = count($color_reserved);
                    
                    //同じスタイリストの同時刻のカット取得
                    $cut_time = stylist_cut::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                     //同じスタイリストの同時刻のパーマ取得
                    $perm_time = stylist_perm::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    //同じスタイリストの同時刻のカット・カラー取得
                    $cut_and_color_time = stylist_cut_and_color::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    //同じスタイリストの同時刻のカット・パーマ取得
                    $cut_and_perm_time = stylist_cut_and_perm::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    if ($num_rows == 0){
                        
                            $reserve= Reserve::insert([
                            "admin_id" => $id,
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                              
                            $stylist_color = stylist_color::insert([
                                "admin_id" => $id,
                                "stylist_id" => $stylist_id,
                                "date" => $input['date'],
                                "startTime" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                           
                            return redirect('/reserved')->withInput($input);
                        
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                    
                        $lists = stylist_color::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value('stylist_id');
                    
                        if ($lists == null && $cut_time != $start_time && $perm_time != $start_time && $cut_and_color_time != $start_time && $cut_and_perm_time != $start_time){
                            $reserve= Reserve::insert([
                            "admin_id" => $id,
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                              
                            $stylist_color = stylist_color::insert([
                                "admin_id" => $id,
                                "stylist_id" => $stylist_id,
                                "date" => $input['date'],
                                "startTime" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            $mail_controller = app()->make('App\Http\Controllers\MailController');
                            $mail_controller->reserveComplete($input);
    
                            return redirect('/reserved')->withInput($input);
                        }else{
                            $date_correction = true;
                            return view('failure_reserve')->with(["date_correction" => $date_correction]);
                        }
                    }
                }elseif ($input['menu'] == "パーマ"){
                    $perm_reserved = Reserve::query()->where("menu","パーマ")->get();
                    $num_rows = count($perm_reserved);
                    
                    //同じスタイリストの同時刻のカット取得
                    $cut_time = stylist_cut::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    //同じスタイリストの同時刻のカラー取得
                    $color_time = stylist_color::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    //同じスタイリストの同時刻のカット・カラー取得
                    $cut_and_color_time = stylist_cut_and_color::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    //同じスタイリストの同時刻のカット・パーマ取得
                    $cut_and_perm_time = stylist_cut_and_perm::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    if ($num_rows == 0){
                        
                            $reserve= Reserve::insert([
                            "admin_id" => $id,
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_perm = stylist_perm::insert([
                                "admin_id" => $id,
                                "stylist_id" => $stylist_id,
                                "date" => $input['date'],
                                "startTime" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        $lists = stylist_perm::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value('stylist_id');
                        
                        if ($lists == null && $cut_time != $start_time && $color_time != $start_time && $cut_and_color_time != $start_time && $cut_and_perm_time != $start_time){
                            $reserve= Reserve::insert([
                            "admin_id" => $id,
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_perm = stylist_perm::insert([
                                "admin_id" => $id,
                                "stylist_id" => $stylist_id,
                                "date" => $input['date'],
                                "startTime" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                        }else{
                            $date_correction = true;
                            return view('failure_reserve')->with(["date_correction" => $date_correction]);
                        }
                    }
                }elseif ($input['menu'] = "カット・カラー"){
                    $cut_color_reserved = Reserve::query()->where("menu","カット・カラー")->get();
                    $num_rows = count($cut_color_reserved);
                    
                    //同じスタイリストの同時刻のカット取得
                    $cut_time = stylist_cut::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    
                    //同じスタイリストの同時刻のカラー取得
                    $color_time = stylist_color::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    //同じスタイリストの同時刻のパーマ取得
                    $perm_time = stylist_perm::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    //同じスタイリストの同時刻のカット・パーマ取得
                    $cut_and_perm_time = stylist_cut_and_perm::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    if ($num_rows == 0){
                            $reserve= Reserve::insert([
                            "admin_id" => $id,
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);   
                                      
                            $stylist_cut_and_color = stylist_cut_and_color::insert([
                                "admin_id" => $id,
                                "stylist_id" => $stylist_id,
                                "date" => $input['date'],
                                "startTime" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                        
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        $lists = stylist_cut_and_color::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value('stylist_id');
                        
                        if ($list == null && $cut_time != $start_time && $color_time != $start_time && $perm_time != $start_time && $cut_and_perm_time != $start_time){
                            $reserve= Reserve::insert([
                            "admin_id" => $id,
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_cut_and_color = stylist_cut_and_color::insert([
                                "admin_id" => $id,
                                "stylist_id" => $stylist_id,
                                "date" => $input['date'],
                                "startTime" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                        }else{
                            $date_correction = true;
                            return view('failure_reserve')->with(["date_correction" => $date_correction]);
                        }
                    }
                }elseif ($input['menu'] == "カット・パーマ"){
                    $cut_perm_reserved = Reserve::query()->where("menu","カット・パーマ")->get();
                    $num_rows = count($cut_perm_reserved);
                    
                    //同じスタイリストの同時刻のカット取得
                    $cut_time = stylist_cut::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    //同じスタイリストの同時刻のカラー取得
                    $color_time = stylist_color::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    //同じスタイリストの同時刻のパーマ取得
                    $perm_time = stylist_perm::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    //同じスタイリストの同時刻のカット・カラー取得
                    $cut_and_color_time = stylist_cut_and_color::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value("startTime");
                    
                    if ($num_rows == 0){
                        
                            $reserve= Reserve::insert([
                            "admin_id" => $id,
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_cut_and_perm = stylist_cut_and_perm::insert([
                                "admin_id" => $id,
                                "stylist_id" => $stylist_id,
                                "date" => $input['date'],
                                "startTime" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                        
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        $lists = stylist_cut_and_perm::query()->where("stylist_id",$stylist_id)->where("date", $input['date'])->where("startTime", $input['time'])->value('stylist_id');
                        
                        if ($lists == null && $cut_time != $start_time && $color_time != $start_time && $perm_time != $start_time && $cut_and_color_time != $start_time){
                            $reserve= Reserve::insert([
                            "admin_id" => $id,
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_cut_and_perm = stylist_cut_and_perm::insert([
                                "admin_id" => $id,
                                "stylist_id" => $stylist_id,
                                "date" => $input['date'],
                                "startTime" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                        }else{
                            $date_correction = true;
                            return view('failure_reserve')->with(["date_correction" => $date_correction]);
                        }
                    }
                }
        }else{
            //ログインしているユーザのID取得
            $user_id = Auth::id();
            
            //スタイリストのID取得
            $stylist_id = Stylist::query()->where('name','LIKE',"%{$input["stylist"]}%")>value('id');
            

            $reserve= Reserve::insert([
                "admin_id" => $id,
                "user_id" =>  $user_id,
                "stylist_id" => $stylist_id,
                "menu" => $input['menu'],
                "date" => $input['date'],
                "startTime" => $input['time'],
                "created_at" => now(),
                "updated_at" => now(),
            ]);
              
            if ($input['menu'] == "カット"){
                $stylist_cut = stylist_cut::insert([
                    "admin_id" => $id,
                    "stylist_id" => $stylist_id,
                    "date" => $input['date'],
                    "startTime" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/reserved')->withInput($input);
            }elseif ($input['menu'] == "カラー"){
                $stylist_color = stylist_color::insert([
                    "admin_id" => $id,
                    "stylist_id" => $stylist_id,
                    "date" => $input['date'],
                    "startTime" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/reserved')->withInput($input);
            }elseif ($input['menu'] == "パーマ"){
                $stylist_perm = stylist_perm::insert([
                    "admin_id" => $id,
                    "stylist_id" => $stylist_id,
                    "date" => $input['date'],
                    "startTime" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/reserved')->withInput($input);
            }elseif ($input['menu'] = "カット・カラー"){
                $stylist_cut_and_color = stylist_cut_and_color::insert([
                    "admin_id" => $id,
                    "stylist_id" => $stylist_id,
                    "date" => $input['date'],
                    "startTime" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/reserved')->withInput($input);
            }elseif ($input['menu'] = "カット・パーマ"){
                $stylist_cut_and_perm = stylist_cut_and_perm::insert([
                    "admin_id" => $id,
                    "stylist_id" => $stylist_id,
                    "date" => $input['date'],
                    "startTime" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/reserved')->withInput($input);
            }
              
            
              
            }
        }
        
    }
    
}
