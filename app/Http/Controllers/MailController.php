<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Reserve;
use App\Stylist;
use App\User;
use App\Menu;
use App\time;
use App\fileImage;
use App\Mail\ReserveConfirm;
use DateTime;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function reserveComplete(Request $request)
    {
        //ログインしているユーザ名の取得
        $user_id = Auth::id();
        $user_name = User::query()->where('id',$user_id)->value('name');
        
        $mail_name = $user_name;
        $mail_text = $user_name.'様';
        
        
    
        $reserves = $request->old();
        
        //日付の表示形式変更
        $reserves['date'] = date('Y年m月d日',strtotime($reserves['date']));
        //メールアドレスの取得
        $mail_to = User::query()->where('id',$user_id)->value('email');
        
        
        Mail::to($mail_to)->send( new ReserveConfirm($mail_name, $mail_text, $reserves) );
        
        return redirect('/')->with('flash_message', '予約が完了しました');
        
        
        
    }
    
    
}
