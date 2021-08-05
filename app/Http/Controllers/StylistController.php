<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Reserve;
use App\Stylist;
use App\User;
use App\Menu;
use App\Admin;
use App\StylistReview;
use App\time;
use App\file_Image;
use DateTime;

class StylistController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('able_time','show_review');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function able_time($id,Request $request)
    {
        $input = $request['search'];
        $salon = Admin::find($id)->first();
        
        //既存の予約が入っている時間の取り出し
        $reserved_times = $salon->reserves()->where('date',$input['date'])->pluck('startTime');
        
        $stylists = Stylist::query()->get();
        $stylist_times = [];
        foreach ($stylists as $stylist){
            $time = $salon->reserves()->where('date',$input['date'])->where('admin_id',$id)->where("stylist_id",$stylist->id)->orderBy('startTime','ASC')->pluck('startTime');
            $stylist_times[$stylist->name] = $time;
        }
        
        //営業時間の取得
        $startTime = time::query()->where("admin_id",$id)->value('startTime');
        $endTime = time::query()->where("admin_id",$id)->value('endTime');
        $diff = strtotime($endTime)-strtotime($startTime);
        $count = $diff/1800;
        $times = [];
        
        
        array_push($times,$startTime);
        for ($i = 1; $i <= $count; $i++){
            $startTime = strtotime('+30 minutes', strtotime($startTime));
            $startTime = date('H:i:s',$startTime);
            array_push($times,$startTime);
        }
        //日にちの取得
        $date = $input['date'];
        $date = date('m月d日',strtotime($date));
        
        

        return view('info_stylists')->with([
            'stylist_times' => $stylist_times,
            'stylists' => $stylists,
            'times' => $times,
            'salon_id' => $id,
            'date' => $date,
            ]);
    }
    
    
    public function show_review($salon_id, $stylist_id){
        $reviews = StylistReview::query()->where('stylist_id',$stylist_id)->get();
        
        //全ユーザとメニューの取得
        $users = User::query()->get();
        
        //評価の平均値取得
        $review_avg = StylistReview::query()->where('stylist_id',$stylist_id)->pluck('evaluation')->avg();
        
        return view('show_review')->with([
            "reviews" => $reviews, 
            "average" => $review_avg,
            "users" => $users,
            ]);
    }
    
    public function show_create_reviw(Request $request){
        $reserves = $request['reserve'];
        $evaluation = [
            '5' => '非常に良かった',
            '4' => '良かった',
            '3' => 'ふつう',
            '2' => '悪かった',
            '1' => '非常に悪かった',
            ];
        return view('create_review')->with([
            "evaluation" => $evaluation, 
            "reserves" => $reserves]);
    }
    
    public function create_review($reserve_id ,Request $request){
        $reseult = false;
        $review = $request['review'];
        $reserve = Reserve::find($reserve_id);
        
        
        $user_id = Auth::id();
        $exists = StylistReview::query()->where('reserve_id', $reserve_id)->exists();
        if($exists){
            die('すでにレビューは投稿済みです。');
        }else{
            $review= StylistReview::insert([
                            "reserve_id" => $reserve_id,
                            "stylist_id" => $reserve->stylist_id,
                            "evaluation" => $review['evaluation'],
                            "comment" => $review['comment'],
                            "created_at" => now(),
                            "updated_at" => now(),
                          ]);

            return redirect('/salon/mypage/past_reserve/{user}');
        }
        
        }
            
            
}
    

