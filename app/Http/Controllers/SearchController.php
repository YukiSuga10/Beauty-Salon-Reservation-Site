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
use Storage;

class SearchController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('result_salon','result_region',"refine_review");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
     public function result_salon(Request $request){
        $search = $request['search'];
        $searchResults = Admin::query()->where("name",'LIKE', "%{$search['salonName']}%")->get();
        $result_num = count($searchResults);
        if ($result_num != 0){
            foreach($searchResults as $result){
                $webp_images[$result->id] = [];
            }
            
            foreach ($searchResults as $result){
                $salon_images = SalonImage::where("admin_id",$result->id)->get();
                foreach($salon_images as $salon_image){
                    $image =  Storage::disk('s3')->url($salon_image->path);
                    $salon_image["path"] = $image;
                }
                array_push($webp_images[$result->id],$salon_images);
            }
            
            return view('search_result')->with([
                "results" => $searchResults->paginate(20),
                "images" => $webp_images,
                "numbers" => $result_num,
                "condition" => $search['salonName']]);
        }
 
        return view('search_result')->with([
            "numbers" => $result_num,
            "condition" => $search['salonName']]);
     }
     
     public function result_region(Request $request){
         $search = $request->input('search');
         
         $searchResults = Admin::query()->where("region",$search[0])->get();
         $result_num = count($searchResults);
         if ($result_num != 0){
            foreach($searchResults as $result){
                $webp_images[$result->id] = [];
             }
            
            foreach ($searchResults as $result){
                $salon_images = SalonImage::where("admin_id",$result->id)->get();
                foreach($salon_images as $salon_image){
                    $image =  Storage::disk('s3')->url($salon_image->path);
                    $salon_image["path"] = $image;
                }
                array_push($webp_images[$result->id],$salon_images);
            } 
            
            return view('search_result')->with([
                 "results" => $searchResults->paginate(20),
                 "images" => $webp_images,
                 "numbers" => $result_num,
                 "condition" => $search[0]
                ]);
         }
         
         return view('search_result')->with([
             "numbers" => $result_num,
             "condition" => $search[0]
            ]);
     }
    
    public function refine_review(Request $request,$id){
        $salon = Admin::query()->where("id",$id)->first();
        $reviews = StylistReview::query()->get();
        $users = User::query()->get();
        
        //絞り込み条件
        $menu = $request["refine"]["menu"];
        $evaluation = $request["refine"]["evaluation"];
        
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
        if (count($salonReviews) == 0){
            $review_avg = 0;
        }else{
            $review_avg = $sum/count($salonReviews);
        }
        
        
        $refine_reviews = [];
        if ($menu != "all" && $evaluation != "all"){
            foreach ($salonReviews as $review){
                if ($review->reserve->menu == $menu && $review->evaluation == (int)$evaluation){
                    array_push($refine_reviews,$review);
                }else{
                    continue;
                }
            }
        }elseif($menu != "all" && $evaluation == "all"){
            foreach ($salonReviews as $review){
                if ($review->reserve->menu == $menu){
                    array_push($refine_reviews,$review);
                }else{
                    continue;
                }
            }
        }elseif($menu == "all" && $evaluation != "all"){
            foreach ($salonReviews as $review){
                if ($review->evaluation == (int)$evaluation){
                    array_push($refine_reviews,$review);
                }else{
                    continue;
                }
            }
        }else{
            foreach ($reviews as $review){
                array_push($refine_reviews,$review);
            }
        }
        
        
        return view("review.salon")->with([
            "reviews" => $refine_reviews,
            "average" => (int)$review_avg,
            "users" => $users,
            "salon" => $salon,
            "refine" => $request["refine"]
            ]);
    }

    
    
}
