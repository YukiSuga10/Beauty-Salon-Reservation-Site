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
use App\SalonImage;
use App\StylistReview;
use DateTime;
use Image;
use Storage;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('start','info_stylist','show_locationPage','show_salon','show_salonPage',"reviewPage");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function show_mypage(){
        $user = Auth::user();

        return view("mypage")->with([
            "user" => $user
            ]);
    }

    public function reserve_confirm()
    {
        $reserves = Reserve::query()->where("user_id",Auth::id())->where('date','>=',date('Y-m-d H:i:s'))->orderBy("date","ASC")->get();

        foreach ($reserves as $reserve){
            $reserve->date = date('Y年m月d日',strtotime($reserve->date));
        }

        return view("reserve_confirm")->with(['reserves' => $reserves]);
    }
    
    public function show_profile(){
        $user = Auth::user();

        return view("profile")->with([
            "user" => $user
            ]);
    }
    
    public function editProfile(Request $request){
        $edit_content = $request['edit'];
        $user = Auth::user();
        
        $profile = User::query()->where("id",$user->id)->first();

        
        $profile->name = $edit_content['name'];
        $profile->gender = $edit_content['gender'];
        $profile->email = $edit_content['email'];
        $profile->save();
        
        return redirect("/home")->with(["flash_message" => "変更が完了しました"]);
    }
    
    //トップページ表示
    public function show_salon(){
        $admin_images = SalonImage::query()->get();
        $admins = Admin::query()->orderBy("id","ASC")->get();
        $reviews = StylistReview::query()->get();
        if (count($admins) != 0){
            //ランキング出力
            $averages = [];
            foreach ($admins as $admin){
                $sum = 0;
                $count = 0;
                foreach($reviews as $review){
                    if ($review->reserve->admin_id == $admin->id){
                        $sum += $review->evaluation;
                        $count += 1;
                    }else{
                        continue;
                    }
                }
                
                if ($count == 0){
                    $average = 0;
                }else{
                    $average = $sum/$count;
                }
                $averages[$admin->id] =
                    array (
                        "id" => $admin->id,
                        "name" => $admin->name,
                        "evaluation" => $average
                        );
            }
           $sort = [];
           foreach ($averages as $key => $value) {
                $sort[$key] = $value['evaluation'];
            }
            array_multisort($sort, SORT_DESC, $averages);
            
            
            if (count($admin_images) == 0){
                return view('first_launch')->with([
                    "admins" => $admins->paginate(20),
                    "averages" => $averages,
                    "count_salon" => count($admins),
                    "count" => count($admin_images)
                    ]);
            }else{
                //連想配列定義
               foreach($admin_images as $images){
                   $webp_images[$images->admin_id] = [];
               }
               
               //pathの変換
               foreach($admin_images as $images){
                   $salon_image =   Storage::disk('s3')->url($images->path);
                    array_push($webp_images[$images->admin_id],$salon_image);
                }
                
                return view('first_launch')->with([
                    "admins" => $admins->paginate(20),
                    "images" => $webp_images,
                    "averages" => $averages,
                    "count_salon" => count($admins),
                    "count" => count($admin_images)
                    ]);
            }
        
        }else{
            return view('first_launch')->with([
                "admins" => $admins->paginate(20),
                "count_salon" => 0
                ]);;
        }
        
        
       
      
  
        
    }
    
    //美容院の各ページ表示
    public function show_salonPage($id){
        $salon = Admin::where("id",$id)->first();
        $images = SalonImage::query()->where("admin_id",$id)->get();
        $webp_images = [];
        foreach($images as $image){
            $salon_image =   Storage::disk('s3')->url($image->path);
            array_push($webp_images,$salon_image);
        }
        return view('salonPage')->with([
            "salon" => $salon,
            "images" => $webp_images]);
    }
    
    
    public function info_stylist($id)
    {
        $stylists = Stylist::query()->where("admin_id",$id)->get();
        $stylist_times = [];
        
        foreach($stylists as $stylist){
            $image = file_Image::where("stylist_id",$stylist->id)->value("path");
            $stylist["path"] = $image;
        }
        
        
        foreach ($stylists as $stylist){
            $stylist_image =   Storage::disk('s3')->url($stylist->path);
            $stylist["path"] = $stylist_image;
        }
    
        
        return view("info_stylists")->with([
            'stylists' => $stylists, 
            'stylist_times' => $stylist_times,
            "salon_id" => $id
            ]);
    }
    
    public function reviewPage($id){
        $salon = Admin::query()->where("id",$id)->first();
        $reviews = StylistReview::query()->get();
        $salonReviews = [];
        $sum = 0;
        foreach ($reviews as $review){
            if ($review->reserve->admin_id == $id){
                array_push($salonReviews,$review);
                $sum += $review->evaluation;
            }else{
                continue;
            }
        }
        //平均値の取得
        if(count($salonReviews) == 0){
            $review_avg = 0;
        }else{
            $review_avg = $sum/count($salonReviews);
        }
    
        //全ユーザの取得
        $users = User::query()->get();
    
        $refine = null;
        
        return view("review.salon")->with([
            "reviews" => $salonReviews,
            "average" => (int)$review_avg,
            "users" => $users,
            "salon" => $salon,
            "refine" => null
            ]);
    }
    
    
    public function edit_confirm($id,Request $request){
        $reserve = Reserve::query()->where("id",$id)->first();
        $edit = $request['edit'];
        $edit["date"] = session()->get('date');
        $edit["stylist"] = session()->get('stylist');

        //日時と時間の表示方式変更
        $date = date('Y年m月d日',strtotime($edit["date"]));
        $time = date('G時i分',strtotime($edit["time"]));

        if ($edit["menu"] == "カット"){
            $time_required = "30分";
        }elseif ($edit["menu"] == "カラー" || $edit["menu"] == "パーマ"){
            $time_required = "1時間";
        }else{
            $time_required = "1時間30分";
        }
        
        $content = "変更確認";
        
        session()->put('edit[date]', $edit['date']);
        session()->put('edit[time]', $edit['time']);
        session()->put('edit[menu]', $edit['menu']);
        session()->put('edit[stylist]', $edit['stylist']);
        
        return view('confirm_reserve')->with([
            'reserve' => $reserve,
            'edit' => $edit,
            'date' => $date,
            'time' => $time,
            'time_require' => $time_required,
            'content' => $content]);
    }
    
    
    
    public function past_reserve($id, Reserve $past_reserves){
        $past_reserves = Reserve::query()->where('user_id',$id)->where('date','<',date('Y-m-d H:i:s'))->orderBy('date', 'ASC')->get()->paginate(5);
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
    
    
    public function show_editForm($id){
        $reserve = Reserve::query()->where("id",$id)->first();
        $stylists = Stylist::query()->where("admin_id",$reserve->admin_id)->get();
        return view('edit_date_stylist')->with([
            "reserve" => $reserve,
            "stylists" => $stylists
            ]);
    }
    
    public function edit_time_menu($id,Request $request){
        $edit_content = $request['edit'];
        session()->put('date', $edit_content['date']);
        session()->put('stylist', $edit_content['stylist']);
        
        $reserve = Reserve::query()->where("id",$id)->first();
        

        //営業時間の取得
        $startTime = time::query()->where("admin_id",$reserve->admin_id)->value('startTime');
        $endTime = time::query()->where("admin_id",$reserve->admin_id)->value('endTime');
        $diff = strtotime($endTime)-strtotime($startTime);
        $count = $diff/1800;
        
        $times = [];
        array_push($times,date('H:i',strtotime($startTime)));
        for ($i = 1;$i<= $count; $i++){
            $startTime = strtotime('+30 minutes', strtotime($startTime));
            $startTime = date('H:i:s',$startTime);
            array_push($times,$startTime);
        }
        
        //予約の入っている時間の取得
        $reserved_time = Reserve::query()->where("stylist_id",$reserve->stylist_id)->where("date",$reserve->date)->pluck('startTime');
        
        foreach ($times as $time){
            if (!(in_array($time, $reserved_time->toArray(), true))){
                continue;
            }else{
                if ($time == $reserve->startTime){
                    continue;
                }else{
                    $times = array_diff($times,array($time));
                }
            }
        }

        
        foreach ($times as $key => $time){
            $time = date('H:i',strtotime($time));
            $times[$key] = $time;
        }

        //メニューの取得
        $salon_menu = [];
            if ($reserve->admin->menus->first()->cut == 1){
                array_push($salon_menu,"カット");
            }
            if ($reserve->admin->menus->first()->color == 1){
                array_push($salon_menu,"カラー");
            }
            if ($reserve->admin->menus->first()->perm == 1){
                array_push($salon_menu,"パーマ");
            }
            if ($reserve->admin->menus->first()->cut・color == 1){
                array_push($salon_menu,"カット・カラー");
            }
            if ($reserve->admin->menus->first()->cut・perm == 1){
                array_push($salon_menu,"カット・パーマ");
            }
            
        
        $reserve->startTime = date("H:i",strtotime($reserve->startTime));    
            
        return view('edit_time_menu')->with([
            "edit" => $edit_content, 
            "menus" => $salon_menu,
            "times" => $times,
            "reserve" => $reserve]);;
    }
    
    
    public function update($id){
        //変更情報の取得
        $edit["date"] = session()->get('edit[date]');
        $edit["stylist"] = session()->get('edit[stylist]');
        $edit["startTime"] = session()->get('edit[time]');
        $edit["menu"] = session()->get('edit[menu]');
        
        //既存の予約の取得
        $reserve = Reserve::query()->where("id",$id)->first();
        $reserve->date = $edit['date'];
        $reserve->stylist = $edit['stylist'];
        $reserve->startTime = $edit['startTime'];
        $reserve->menu = $edit['menu'];
        $reserve->update();
        
        return redirect('/salon/mypage')->with(["flash_message" => "変更が完了しました"]);
    }
    
    public function delete($reserve_id){
        $reserve = Reserve::where("id",$reserve_id)->first();
        $reserve->delete();
        return redirect('/home')->with(['flash_message' => "予約がキャンセルされました"]);
    }
    
    
    
}
