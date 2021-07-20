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
        $query_user = User::query();
        $query_user -> where('id',$user_id);
        $user_name = $query_user->value('name');
        
        $mail_name = $user_name;
        $mail_text = $user_name.'様';
        
        
        $reserves = $request->old();
        
        //メニューの取得
        $reserves['menu'] = Menu::query()->where('id',$reserves['menu'])->value('menu');
        
        //日付の表示形式変更
        $reserves['date'] = date('Y年m月d日',strtotime($reserves['date']));
        //メールアドレスの取得
        $user_mail = $query_user->value('email');
        
        $mail_to = $user_mail;
  
        
        Mail::to($mail_to)->send( new ReserveConfirm($mail_name, $mail_text, $reserves) );
        
        return redirect('/')->with('flash_message', '予約が完了しました');
        
        
        
    }
    
    
}
