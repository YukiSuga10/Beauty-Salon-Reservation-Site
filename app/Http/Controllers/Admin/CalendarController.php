<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Calendar\CalendarView;
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
use Carbon\carbon;

class CalendarController extends Controller
{
    public function show_calender($id){
        
		$calendar = new CalendarView(time());
		
		$month = Carbon::now()->format("Y-n");
		
		session()->put('month', $month);
		
		$pre_month = strtotime('-1 month', strtotime($month));
        $pre_month = date('Y-n',$pre_month);
        
        $next_month = strtotime('+1 month', strtotime($month));
		$next_month = date('Y-n',$next_month);
		
		
		return view('admin.calendar', [
			"calendar" => $calendar,
			"pre_month" => $pre_month,
			"next_month" => $next_month,
			"id" => $id,
			"content" => "now"
		]);
    }
    
    public function pre_month($id,$month){
        $calendar = new CalendarView(time());

        $pre_month = strtotime('-1 month', strtotime($month));
        $pre_month = date('Y-n',$pre_month);
        
        $next_month = strtotime('+1 month', strtotime($month));
		$next_month = date('Y-n',$next_month);
		
		session()->put('month', $month);
        
        return view("admin.calendar")->with([
            "calendar" => $calendar,
            "month" => $month,
            "pre_month" => $pre_month,
			"next_month" => $next_month,
            "id" => $id,
            "content" => "pre"
            ]);
    }
    
    public function next_month($id,$month){
        $calendar = new CalendarView(time());

        $pre_month = strtotime('-1 month', strtotime($month));
        $pre_month = date('Y-n',$pre_month);
        
        $next_month = strtotime('+1 month', strtotime($month));
		$next_month = date('Y-n',$next_month);
		
		session()->put('month', $month);
        
        return view("admin.calendar")->with([
            "calendar" => $calendar,
            "month" => $month,
            "pre_month" => $pre_month,
			"next_month" => $next_month,
            "id" => $id,
            "content" => "next"
            ]);
    }
    
    
    public function date_reserves($id,$date){
    	$reserves = Reserve::query()->where("admin_id",(int)$id)->where("date",$date)->get();
    	$stylists = Stylist::query()->where("admin_id",(int)$id)->get();
    	

    	//営業時間の取得
        $startTime = time::query()->where("admin_id",$id)->value('startTime');
        $endTime = time::query()->where("admin_id",$id)->value('endTime');
        
        $diff = strtotime($endTime)-strtotime($startTime);
        $count = $diff/1800;
        
        $times = [];
        array_push($times,date('H:i:s',strtotime($startTime)));
        for ($i = 1;$i<= $count; $i++){
            $startTime = strtotime('+30 minutes', strtotime($startTime));
            $startTime = date('H:i:s',$startTime);
            array_push($times,$startTime);
        }
        
        $stylists_appoints = [];
        
        foreach($times as $time){
            foreach ($stylists as $stylist){
                $stylists_appoints[date('H:i',strtotime($time))][$stylist->name] = "×";
            }
        }
        

        foreach ($reserves as $reserve){
            foreach($times as $time){
                foreach($stylists as $stylist){
                    if ($reserve->stylist_id == $stylist->id){
                        if ($reserve->startTime == $time){
                            $stylists_appoints[date('H:i',strtotime($time))][$stylist->name] = [];
                            $stylists_appoints[date('H:i',strtotime($time))][$stylist->name]["予約状況"] = $reserve;;
                        }else{
                            continue;
                        }
                    }else{
                        continue;
                    }
                }
            }
        }
        
    	return view("admin.reserve_lists")->with([
    	    "date" => date("Y年m月d日",strtotime($date)),
    		"stylists" => $stylists,
    		"stylists_appoint" => $stylists_appoints]);
    }
    
    public function show_reserve($id){
        $reserve = Reserve::query()->where("id",$id)->first();
        dd($reserve->user());
        
        return view("admin.reserve_detail")->with([
            "reserve" => $reserve
            ]);
    }
}