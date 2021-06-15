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
use DateTime;

class ReserveController extends Controller
{
    public function reserve(Request $request, Reserve $reserve, stylist_color $stylist_color, stylist_cut $stylist_cut, stylist_perm $stylist_perm,Menu $menu, stylist_cut_and_color $stylist_cut_and_color, stylist_cut_and_perm $stylist_cut_and_perm){
        //データベースのレコード数取得
        $con = mysqli_connect('localhost', 'dbuser', 'yuki121028', 'salon');
        if(!$con) {
            die('接続に失敗しました');
        }
        // 文字コード
        mysqli_set_charset($con, 'utf8');
        // SQLの発行と出力
        $sql = "SELECT * FROM reserves";
        $res = mysqli_query($con, $sql);
        $num_rows = mysqli_num_rows($res);

        mysqli_close($con);
        
        //
        
        if ($num_rows > 0){
            $input = $request['reserve'];
            
            $user_id = Auth::id();
            //スタイリストのID取得
            $query = Stylist::query();
            $query -> where('name','LIKE',"%{$input["stylist"]}%");
            $stylist_id = $query->value('id');
            //メニュー名取得
            $query = Menu::query();
            $query -> where('id','LIKE',"%{$input["menu"]}%");
            $menu = $query->value('menu');
            
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
                $query_color = stylist_color::query();
                $query_color -> where("stylist_id",$stylist_id);
                $query_color -> where("予約日時", $input['date']);
                $query_color -> where("開始時間", $input['time']);
                $color_time = $query_color->value("開始時間");
                
                //同じスタイリストの同時刻のパーマ取得
                $query_perm = stylist_perm::query();
                $query_perm -> where("stylist_id",$stylist_id);
                $query_perm -> where("予約日時", $input['date']);
                $query_perm -> where("開始時間", $input['time']);
                $perm_time = $query_perm->value("開始時間");
                
                //同じスタイリストの同時刻のカット・カラー取得
                $query_cut_color = stylist_cut_and_color::query();
                $query_cut_color -> where("stylist_id",$stylist_id);
                $query_cut_color -> where("予約日時", $input['date']);
                $cut_and_color_time = $query_cut_color->value("開始時間");
                
                //同じスタイリストの同時刻のカット・パーマ取得
                $query_cut_perm = stylist_cut_and_perm::query();
                $query_cut_perm -> where("stylist_id",$stylist_id);
                $query_cut_perm -> where("予約日時", $input['date']);
                $cut_and_perm_time = $query_cut_perm->value("開始時間");
                

                //レコードの数で条件分岐
                if ($num_rows == 0){
                    //inputをTIME型に変換
                    $start_time = strtotime($input["time"]);
                    $start_time = date("H:i:s",$start_time);
                    
                    if ($color_time != $start_time && $perm_time != $start_time && $cut_and_color_time != $start_time && $cut_and_perm_time != $start_time){
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
                        return redirect('/')->with('flash_message', '予約が完了しました');
                    }else{
                        dd("失敗");
                    }
                }else{
                    //inputをTIME型に変換
                    $start_time = strtotime($input["time"]);
                    $start_time = date("H:i:s",$start_time);
                    
                    $query = stylist_cut::query();
                    $query -> where("stylist_id",$stylist_id);
                    $query -> where("予約日時", $input['date']);
                    $query -> where("開始時間", $input['time']);
                    $lists = $query->value('stylist_id');
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
                        return redirect('/')->with('flash_message', '予約が完了しました');
                    }else{
                        dd("失敗");
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
                    $query_cut = stylist_cut::query();
                    $query_cut -> where("stylist_id",$stylist_id);
                    $query_cut -> where("予約日時", $input['date']);
                    $query_cut -> where("開始時間", $input['time']);
                    $cut_time = $query_cut->value("開始時間");
                    
                     //同じスタイリストの同時刻のパーマ取得
                    $query_perm = stylist_perm::query();
                    $query_perm -> where("stylist_id",$stylist_id);
                    $query_perm -> where("予約日時", $input['date']);
                    $query_perm -> where("開始時間", $input['time']);
                    $perm_time = $query_perm->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・カラー取得
                    $query_cut_color = stylist_cut_and_color::query();
                    $query_cut_color -> where("stylist_id",$stylist_id);
                    $query_cut_color -> where("予約日時", $input['date']);
                    $cut_and_color_time = $query_cut_color->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・パーマ取得
                    $query_cut_perm = stylist_cut_and_perm::query();
                    $query_cut_perm -> where("stylist_id",$stylist_id);
                    $query_cut_perm -> where("予約日時", $input['date']);
                    $cut_and_perm_time = $query_cut_perm->value("開始時間");
                    
                    if ($num_rows == 0){
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        if ($cut_time != $start_time && $perm_time != $start_time && $cut_and_color_time != $start_time && $cut_and_perm_time != $start_time){
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
                            return redirect('/')->with('flash_message', '予約が完了しました');
                        }else{
                            dd("失敗");
                        }
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                    
                        $query = stylist_color::query();
                        $query -> where('stylist_id',$stylist_id);
                        $query -> where('予約日時', $input['date']);
                        $query -> where('開始時間', $input['time']);
                        $lists = $query -> value('stylist_id');
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
                            return redirect('/')->with('flash_message', '予約が完了しました');
                        }else{
                            dd("失敗");
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
                    $query_cut = stylist_cut::query();
                    $query_cut -> where("stylist_id",$stylist_id);
                    $query_cut -> where("予約日時", $input['date']);
                    $query_cut -> where("開始時間", $input['time']);
                    $cut_time = $query_cut->value("開始時間");
                    
                     //同じスタイリストの同時刻のカラー取得
                    $query_color = stylist_color::query();
                    $query_color -> where("stylist_id",$stylist_id);
                    $query_color -> where("予約日時", $input['date']);
                    $query_color -> where("開始時間", $input['time']);
                    $color_time = $query_color->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・カラー取得
                    $query_cut_color = stylist_cut_and_color::query();
                    $query_cut_color -> where("stylist_id",$stylist_id);
                    $query_cut_color -> where("予約日時", $input['date']);
                    $cut_and_color_time = $query_cut_color->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・パーマ取得
                    $query_cut_perm = stylist_cut_and_perm::query();
                    $query_cut_perm -> where("stylist_id",$stylist_id);
                    $query_cut_perm -> where("予約日時", $input['date']);
                    $cut_and_perm_time = $query_cut_perm->value("開始時間");
                    
                    if ($num_rows == 0){
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        if ($cut_time != $start_time && $color_time != $start_time && $cut_and_color_time != $start_time && $cut_and_perm_time != $start_time){
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
                            return redirect('/')->with('flash_message', '予約が完了しました');
                        }else{
                            dd("失敗");
                        }
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        $query = stylist_perm::query();
                        $query -> where('stylist_id',$stylist_id);
                        $query -> where('予約日時', $input['date']);
                        $query -> where('開始時間', $input['time']);
                        $lists = $query -> value('stylist_id');
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
                            return redirect('/')->with('flash_message', '予約が完了しました');
                        }else{
                            dd("失敗");
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
                    $query_cut = stylist_cut::query();
                    $query_cut -> where("stylist_id",$stylist_id);
                    $query_cut -> where("予約日時", $input['date']);
                    $query_cut -> where("開始時間", $input['time']);
                    $cut_time = $query_cut->value("開始時間");
                    
                     //同じスタイリストの同時刻のカラー取得
                    $query_color = stylist_color::query();
                    $query_color -> where("stylist_id",$stylist_id);
                    $query_color -> where("予約日時", $input['date']);
                    $query_color -> where("開始時間", $input['time']);
                    $color_time = $query_color->value("開始時間");
                    
                    //同じスタイリストの同時刻のパーマ取得
                    $query_perm = stylist_perm::query();
                    $query_perm -> where("stylist_id",$stylist_id);
                    $query_perm -> where("予約日時", $input['date']);
                    $query_perm -> where("開始時間", $input['time']);
                    $perm_time = $query_perm->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・パーマ取得
                    $query_cut_perm = stylist_cut_and_perm::query();
                    $query_cut_perm -> where("stylist_id",$stylist_id);
                    $query_cut_perm -> where("予約日時", $input['date']);
                    $cut_and_perm_time = $query_cut_perm->value("開始時間");
                    
                    if ($num_rows == 0){
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        if ($cut_time != $start_time && $color_time != $start_time && $perm_time != $start_time && $cut_and_perm_time != $start_time){
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
                            return redirect('/')->with('flash_message', '予約が完了しました');
                        }else{
                            dd("失敗");
                        }
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        $query = stylist_cut_and_color::query();
                        $query -> where('stylist_id',$stylist_id);
                        $query -> where('予約日時', $input['date']);
                        $query -> where('開始時間', $input['time']);
                        $lists = $query -> value('stylist_id');
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
                            return redirect('/')->with('flash_message', '予約が完了しました');
                        }else{
                            dd("失敗");
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
                    $query_cut = stylist_cut::query();
                    $query_cut -> where("stylist_id",$stylist_id);
                    $query_cut -> where("予約日時", $input['date']);
                    $query_cut -> where("開始時間", $input['time']);
                    $cut_time = $query_cut->value("開始時間");
                    
                     //同じスタイリストの同時刻のカラー取得
                    $query_color = stylist_color::query();
                    $query_color -> where("stylist_id",$stylist_id);
                    $query_color -> where("予約日時", $input['date']);
                    $query_color -> where("開始時間", $input['time']);
                    $color_time = $query_color->value("開始時間");
                    
                    //同じスタイリストの同時刻のパーマ取得
                    $query_perm = stylist_perm::query();
                    $query_perm -> where("stylist_id",$stylist_id);
                    $query_perm -> where("予約日時", $input['date']);
                    $query_perm -> where("開始時間", $input['time']);
                    $perm_time = $query_perm->value("開始時間");
                    
                    //同じスタイリストの同時刻のカット・カラー取得
                    $query_cut_color = stylist_cut_and_color::query();
                    $query_cut_color -> where("stylist_id",$stylist_id);
                    $query_cut_color -> where("予約日時", $input['date']);
                    $cut_and_color_time = $query_cut_color->value("開始時間");
                    
                    if ($num_rows == 0){
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        if ($cut_time != $start_time && $color_time != $start_time && $perm_time != $start_time && $cut_and_color_time != $start_time){
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
                            return redirect('/')->with('flash_message', '予約が完了しました');
                        }else{
                            dd("失敗");
                        }
                    }else{
                        //inputをTIME型に変換
                        $start_time = strtotime($input["time"]);
                        $start_time = date("H:i:s",$start_time);
                        
                        $query = stylist_cut_and_perm::query();
                        $query -> where('stylist_id',$stylist_id);
                        $query -> where('予約日時', $input['date']);
                        $query -> where('開始時間', $input['time']);
                        $lists = $query -> value('stylist_id');
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
                            return redirect('/')->with('flash_message', '予約が完了しました');
                        }else{
                            dd("失敗");
                        }
                    }
                }
        }else{
            $input = $request['reserve'];
            
            //ログインしているユーザのID取得
            $user_id = Auth::id();
            //スタイリストのID取得
            $query = Stylist::query();
            $query -> where('name','LIKE',"%{$input["stylist"]}%");
            $stylist_id = $query->value('id');

            
            //メニュー名取得
            $query = Menu::query();
            $query -> where('id','LIKE',"%{$input['menu']}%");
            $menu = $query->value('menu');  
            
            
            
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
                return redirect('/')->with('flash_message', '予約が完了しました');
            }elseif ($menu == "カラー"){
                $stylist_color = stylist_color::insert([
                    "stylist_id" => $stylist_id,
                    "予約日時" => $input['date'],
                    "開始時間" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/')->with('flash_message', '予約が完了しました');
            }elseif ($menu == "パーマ"){
                $stylist_perm = stylist_perm::insert([
                    "stylist_id" => $stylist_id,
                    "予約日時" => $input['date'],
                    "開始時間" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/')->with('flash_message', '予約が完了しました');
            }elseif ($menu = "カット・カラー"){
                $stylist_cut_and_color = stylist_cut_and_color::insert([
                    "stylist_id" => $stylist_id,
                    "予約日時" => $input['date'],
                    "開始時間" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/')->with('flash_message', '予約が完了しました');
            }elseif ($menu = "カット・パーマ"){
                $stylist_cut_and_perm = stylist_cut_and_perm::insert([
                    "stylist_id" => $stylist_id,
                    "予約日時" => $input['date'],
                    "開始時間" => $input['time'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
                return redirect('/')->with('flash_message', '予約が完了しました');
            }
              
            
              
        }
    }
}
