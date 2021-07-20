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
use App\Menu;
use App\time;
use DateTime;

class ReserveController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function reserve_date_stylist()
    {
        //スタイリストの取得
        $query = Stylist::query();
        $stylists = $query->pluck('name');
        
        return view('reserve_date_stylist')->with([
            "stylists" => $stylists
            ]);
    }
    
    
    public function reserve_time_menu(Request $request)
    {
        $input = $request['reserve'];
        
        //時間の取得
        $time = time::query()->pluck('time');
        
        //時間の表示形式変更
        $times = [];
        foreach ($time as $t){
            $t = date('H:i',strtotime($t));
            array_push($times,$t);
        }
        
         //メニューとそのidの取得
        $menu_id = Menu::query()->pluck('id');
        $menus = Menu::query()->get();
        
        
        //日付の表示形式変更
        $date = $input['date'];
        $date = date('Y年m月d日',strtotime($input["date"]));
        
        //スタイリストIDの取得
        $stylist_id = Stylist::query()->where('name','LIKE',"%{$input["stylist"]}%") ->value('id');
        
        //予約可能時間の取得
        $notPossible_time = Reserve::query()->where('date',$input['date'])->where('stylist_id',$stylist_id)->pluck('startTime');

        if (count($notPossible_time) == 0 ){
            //時間の取得
            $possible_time = time::query()->pluck('time');
        }else{
            //時間の取得
            $possible_time = [];
            foreach (time::query()->pluck('time') as $time){
                if (!(in_array($time,$notPossible_time->toArray()))){
                    array_push($possible_time,$time);
                }
            }
            
        }

        //時間の表示形式変更
        $times = [];
        foreach ($possible_time as $t){
            $t = date('H:i',strtotime($t));
            array_push($times,$t);
        }
        
        return view('reserve_time_menu')->with([
            "times" => $times,
            "menus" => $menus,
            "reserve" => $input,
            "date" => $date
            ]);
    }
    
    public function reserve(Request $request, Reserve $reserve, stylist_color $stylist_color, stylist_cut $stylist_cut, stylist_perm $stylist_perm,Menu $menu, stylist_cut_and_color $stylist_cut_and_color, stylist_cut_and_perm $stylist_cut_and_perm){
        //入力された日にちが過去の場合
        $input = $request['reserve'];
        $date = strtotime($input['date']);
        $dateNow = strtotime(date('Y-m-d'));
        if ($date < $dateNow){
            $date_correction = false;
            return view('failure_reserve')->with(["date_correction" => $date_correction]);
        }else{
        //データベースのレコード数取得
        $con = mysqli_connect('localhost', 'dbuser', 'yuki121028', 'salon');
        if(!$con) {
            die('接続に失敗しました');
        }
        mysqli_set_charset($con, 'utf8');
        // SQLの発行と出力
        $sql = "SELECT * FROM reserves";
        $res = mysqli_query($con, $sql);
        $num_rows = mysqli_num_rows($res);
        mysqli_close($con);
        
        if ($num_rows > 0){
            $input = $request['reserve'];
            $user_id = Auth::id();
            
            //スタイリストのID取得
            $stylist_id = Stylist::query()->where('name','LIKE',"%{$input["stylist"]}%")>value('id');
            
            //メニュー名取得
            $menu = Menu::query()->where('id','LIKE',"%{$input["menu"]}%")->value('menu');
            
            if ($menu == "カット"){
                //データベースの接続
                $con = mysqli_connect('localhost', 'dbuser', 'yuki121028', 'salon');
                if(!$con) {
                    die('接続に失敗しました');
                }
                // 文字コード
                mysqli_set_charset($con, 'utf8');
                // SQLの発行と出力
                $sql = "SELECT * FROM stylist_cuts";
                $res = mysqli_query($con, $sql);
                $num_rows = mysqli_num_rows($res);
        
                mysqli_close($con);
                
                //同じスタイリストの同時刻のカラー取得
                $color_time = stylist_color::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                
                //同じスタイリストの同時刻のパーマ取得
                $perm_time = stylist_perm::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                
                //同じスタイリストの同時刻のカット・カラー取得
                $cut_and_color_time = stylist_cut_and_color::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                
                //同じスタイリストの同時刻のカット・パーマ取得
                $cut_and_perm_time = stylist_cut_and_perm::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                
                //レコードの数で条件分岐
                if ($num_rows == 0){
                        $reserve= Reserve::insert([
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu_id" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                          ]);
                          
                        $stylist_cut = stylist_cut::insert([
                            "stylist_id" => $stylist_id,
                            "予約日時" => $input['date'],
                            "開始時間" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                        ]);
                        
                        return redirect('/reserved')->withInput($input);
                    
                }else{
                    //inputをTIME型に変換
                    $start_time = strtotime($input["time"]);
                    $start_time = date("H:i:s",$start_time);
                    
                    $lists = stylist_cut::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value('stylist_id');
                    
                    if ($lists == null && $color_time != $start_time && $perm_time != $start_time && $cut_and_color_time != $start_time && $cut_and_perm_time != $start_time){
                        //予約テーブルへのDB書き込み
                            $reserve= Reserve::insert([
                                "user_id" =>  $user_id,
                                "stylist_id" => $stylist_id,
                                "menu_id" => $input['menu'],
                                "date" => $input['date'],
                                "startTime" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                              ]);
                              
                         //stylist_cutテーブルへのDB書き込み
                            $stylist_cut = stylist_cut::insert([
                            "stylist_id" => $stylist_id,
                            "予約日時" => $input['date'],
                            "開始時間" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                        ]);
                        
                        return redirect('/reserved')->withInput($input);
                    }else{
                        $date_correction = true;
                        return view('failure_reserve')->with(["date_correction" => $date_correction]);
                    }
                }
            }elseif ($menu == "カラー"){
                    //データベースの接続
                    $con = mysqli_connect('localhost', 'dbuser', 'yuki121028', 'salon');
                    if(!$con) {
                        die('接続に失敗しました');
                    }
                    // 文字コード
                    mysqli_set_charset($con, 'utf8');
                    // SQLの発行と出力
                    $sql = "SELECT * FROM stylist_colors";
                    $res = mysqli_query($con, $sql);
                    $num_rows = mysqli_num_rows($res);
            
                    mysqli_close($con);
                    
                    //同じスタイリストの同時刻のカット取得
                    $cut_time = stylist_cut::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                     //同じスタイリストの同時刻のパーマ取得
                    $perm_time = stylist_perm::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・カラー取得
                    $cut_and_color_time = stylist_cut_and_color::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・パーマ取得
                    $cut_and_perm_time = stylist_cut_and_perm::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    if ($num_rows == 0){
                        
                            $reserve= Reserve::insert([
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu_id" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                              
                            $stylist_color = stylist_color::insert([
                                "stylist_id" => $stylist_id,
                                "予約日時" => $input['date'],
                                "開始時間" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                           
                            return redirect('/reserved')->withInput($input);
                        
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                    
                        $lists = stylist_color::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value('stylist_id');
                    
                        if ($lists == null && $cut_time != $start_time && $perm_time != $start_time && $cut_and_color_time != $start_time && $cut_and_perm_time != $start_time){
                            $reserve= Reserve::insert([
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu_id" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                              
                            $stylist_color = stylist_color::insert([
                                "stylist_id" => $stylist_id,
                                "予約日時" => $input['date'],
                                "開始時間" => $input['time'],
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
                }elseif ($menu == "パーマ"){
                    //データベースの接続
                    $con = mysqli_connect('localhost', 'dbuser', 'yuki121028', 'salon');
                    if(!$con) {
                        die('接続に失敗しました');
                    }
                    // 文字コード
                    mysqli_set_charset($con, 'utf8');
                    // SQLの発行と出力
                    $sql = "SELECT * FROM stylist_colors";
                    $res = mysqli_query($con, $sql);
                    $num_rows = mysqli_num_rows($res);
            
                    mysqli_close($con);
                    
                    //同じスタイリストの同時刻のカット取得
                    $cut_time = stylist_cut::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    //同じスタイリストの同時刻のカラー取得
                    $color_time = stylist_color::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・カラー取得
                    $cut_and_color_time = stylist_cut_and_color::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・パーマ取得
                    $cut_and_perm_time = stylist_cut_and_perm::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    if ($num_rows == 0){
                        
                            $reserve= Reserve::insert([
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu_id" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_perm = stylist_perm::insert([
                                "stylist_id" => $stylist_id,
                                "予約日時" => $input['date'],
                                "開始時間" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        $lists = stylist_perm::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value('stylist_id');
                        
                        if ($lists == null && $cut_time != $start_time && $color_time != $start_time && $cut_and_color_time != $start_time && $cut_and_perm_time != $start_time){
                            $reserve= Reserve::insert([
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu_id" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_perm = stylist_perm::insert([
                                "stylist_id" => $stylist_id,
                                "予約日時" => $input['date'],
                                "開始時間" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                        }else{
                            $date_correction = true;
                            return view('failure_reserve')->with(["date_correction" => $date_correction]);
                        }
                    }
                }elseif ($menu = "カット・カラー"){
                    //データベースの接続
                    $con = mysqli_connect('localhost', 'dbuser', 'yuki121028', 'salon');
                    if(!$con) {
                        die('接続に失敗しました');
                    }
                    // 文字コード
                    mysqli_set_charset($con, 'utf8');
                    // SQLの発行と出力
                    $sql = "SELECT * FROM stylist_cut_and_colors";
                    $res = mysqli_query($con, $sql);
                    $num_rows = mysqli_num_rows($res);
                    
                    mysqli_close($con);
                    
                    //同じスタイリストの同時刻のカット取得
                    $cut_time = stylist_cut::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    
                    //同じスタイリストの同時刻のカラー取得
                    $color_time = stylist_color::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    //同じスタイリストの同時刻のパーマ取得
                    $perm_time = stylist_perm::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・パーマ取得
                    $cut_and_perm_time = stylist_cut_and_perm::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    if ($num_rows == 0){
                            $reserve= Reserve::insert([
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu_id" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_cut_and_color = stylist_cut_and_color::insert([
                                "stylist_id" => $stylist_id,
                                "予約日時" => $input['date'],
                                "開始時間" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                        
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        $lists = stylist_cut_and_color::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value('stylist_id');
                        
                        if ($list == null && $cut_time != $start_time && $color_time != $start_time && $perm_time != $start_time && $cut_and_perm_time != $start_time){
                            $reserve= Reserve::insert([
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu_id" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_cut_and_color = stylist_cut_and_color::insert([
                                "stylist_id" => $stylist_id,
                                "予約日時" => $input['date'],
                                "開始時間" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                        }else{
                            $date_correction = true;
                            return view('failure_reserve')->with(["date_correction" => $date_correction]);
                        }
                    }
                }elseif ($menu == "カット・パーマ"){
                    //データベースの接続
                    $con = mysqli_connect('localhost', 'dbuser', 'yuki121028', 'salon');
                    if(!$con) {
                        die('接続に失敗しました');
                    }
                    // 文字コード
                    mysqli_set_charset($con, 'utf8');
                    // SQLの発行と出力
                    $sql = "SELECT * FROM stylist_cut_and_perms";
                    $res = mysqli_query($con, $sql);
                    $num_rows = mysqli_num_rows($res);
                    
                    mysqli_close($con);
                    
                    //同じスタイリストの同時刻のカット取得
                    $cut_time = stylist_cut::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    //同じスタイリストの同時刻のカラー取得
                    $color_time = stylist_color::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    //同じスタイリストの同時刻のパーマ取得
                    $perm_time = stylist_perm::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・カラー取得
                    $cut_and_color_time = stylist_cut_and_color::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value("開始時間");
                    
                    if ($num_rows == 0){
                        
                            $reserve= Reserve::insert([
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu_id" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_cut_and_perm = stylist_cut_and_perm::insert([
                                "stylist_id" => $stylist_id,
                                "予約日時" => $input['date'],
                                "開始時間" => $input['time'],
                                "created_at" => now(),
                                "updated_at" => now(),
                            ]);
                            return redirect('/reserved')->withInput($input);
                        
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        $lists = stylist_cut_and_perm::query()->where("stylist_id",$stylist_id)->where("予約日時", $input['date'])->where("開始時間", $input['time'])->value('stylist_id');
                        
                        if ($lists == null && $cut_time != $start_time && $color_time != $start_time && $perm_time != $start_time && $cut_and_color_time != $start_time){
                            $reserve= Reserve::insert([
                            "user_id" =>  $user_id,
                            "stylist_id" => $stylist_id,
                            "menu_id" => $input['menu'],
                            "date" => $input['date'],
                            "startTime" => $input['time'],
                            "created_at" => now(),
                            "updated_at" => now(),
                            ]);
                                      
                            $stylist_cut_and_perm = stylist_cut_and_perm::insert([
                                "stylist_id" => $stylist_id,
                                "予約日時" => $input['date'],
                                "開始時間" => $input['time'],
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
            $input = $request['reserve'];
            
            //ログインしているユーザのID取得
            $user_id = Auth::id();
            
            //スタイリストのID取得
            $stylist_id = Stylist::query()->where('name','LIKE',"%{$input["stylist"]}%")>value('id');
            
            //メニュー名取得
            $menu = Menu::query()->where('id','LIKE',"%{$input['menu']}%")->value('menu');
            
            $reserve= Reserve::insert([
                "user_id" =>  $user_id,
                "stylist_id" => $stylist_id,
                "menu_id" => $input['menu'],
                "date" => $input['date'],
                "startTime" => $input['time'],
                "created_at" => now(),
                "updated_at" => now(),
              ]);
              
            if ($menu == "カット"){
                $stylist_cut = stylist_cut::insert([
                    "stylist_id" => $stylist_id,
                    "予約日時" => $input['date'],
                    "開始時間" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/reserved')->withInput($input);
            }elseif ($menu == "カラー"){
                $stylist_color = stylist_color::insert([
                    "stylist_id" => $stylist_id,
                    "予約日時" => $input['date'],
                    "開始時間" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/reserved')->withInput($input);
            }elseif ($menu == "パーマ"){
                $stylist_perm = stylist_perm::insert([
                    "stylist_id" => $stylist_id,
                    "予約日時" => $input['date'],
                    "開始時間" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/reserved')->withInput($input);
            }elseif ($menu = "カット・カラー"){
                $stylist_cut_and_color = stylist_cut_and_color::insert([
                    "stylist_id" => $stylist_id,
                    "予約日時" => $input['date'],
                    "開始時間" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/reserved')->withInput($input);
            }elseif ($menu = "カット・パーマ"){
                $stylist_cut_and_perm = stylist_cut_and_perm::insert([
                    "stylist_id" => $stylist_id,
                    "予約日時" => $input['date'],
                    "開始時間" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/reserved')->withInput($input);
            }
              
            
              
            }
        }
        
    }
    
}
