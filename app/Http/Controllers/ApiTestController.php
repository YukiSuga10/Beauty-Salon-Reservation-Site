<?php

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use App\Reserve;
use App\User;
use App\Stylist;
use App\Menu;
use DateTime;

class ApiTestController extends Controller
{
    public function test(){
        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);
        
        $calendarId = env('GOOGLE_CALENDAR_ID');
    
        $reserves = Reserve::get();
        
        
        foreach ($reserves as $reserve){
            //ユーザ名の取得
            $user_name[$reserve->user_id] =  User::query()->where('id',$reserve->user_id)->value('name');
            
            //スタイリストの取得
            $stylist[$reserve->stylist_id] = Stylist::query()->where('id',$reserve->stylist_id)->value('name');
            
            //メニューの取得
            $menu[$reserve->menu_id] = Menu::query()->where('id',$reserve->menu_id)->value('menu');
        }
        
        
        foreach ($reserves as $reserve){
            
            $event = new Google_Service_Calendar_Event(array(
                'summary' => $user_name[$reserve['user_id']],
                'description' => "スタイリスト：".$stylist[$reserve->stylist_id].". メニュー：".$menu[$reserve->menu_id],
                'start' => array(
                    // 開始日時
                    'dateTime' => $this->getStartTime($reserve->date, $reserve->startTime),
                    'timeZone' => 'Asia/Tokyo',
                ),
                'end' => array(
                    // 終了日時
                    'dateTime' => $this->getEndTime($reserve->date, $reserve->startTime,$reserve->menu_id),
                    'timeZone' => 'Asia/Tokyo',
                ),
            ));
            
            $event = $service->events->insert($calendarId, $event);
   
        }
        echo "イベントを追加しました";
    }
    
    private function getStartTime($date, $startTime){
        $dateTime_start = $date.'T'.$startTime.'+09:00';
        return $dateTime_start;
    }
    
    
    private function getEndTime($date,$startTime,$menu_id){
        //メニューの取得
        $menu = Menu::query()->where('id',$menu_id)->value('menu');
        
        if ($menu == 'カット'){
            $endTime = strtotime('+30 minute',strtotime($startTime));
            $endTime = date('H:i:s',$endTime);
            $dateTime_end = $date.'T'.$endTime.'+09:00';
            return $dateTime_end;
        }elseif ($menu == 'カラー' || $menu == 'パーマ'){
            $endTime = strtotime('+60 minute',strtotime($startTime));
            $endTime = date('H:i:s',$endTime);
            $dateTime_end = $date.'T'.$endTime.'+09:00';
            return $dateTime_end;
        }else{
            $endTime = strtotime('+90 minute',strtotime($startTime));
            $endTime = date('H:i:s',$endTime);
            $dateTime_end = $date.'T'.$endTime.'+09:00';
            return $dateTime_end;
        }
    }


    
    
    
    
    
    private function getClient()
    {
        $client = new Google_Client();

        //アプリケーション名
        $client->setApplicationName('GoogleCalendarAPIのテスト');
        //権限の指定
        $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        //JSONファイルの指定
        $client->setAuthConfig(storage_path('app/api-key/my-project-salonreserve-14e892912691.json'));

        return $client;
    }
}
