<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\file_Image;
use App\Admin;
use App\Stylist;
use App\Jobs\Compress;


class ImageController extends Controller
{
    //
    public function register_stylist($id)
    {
        return view('admin.register_stylist')->with(["id" => $id]);
    }
    
    
    public function upload($id,Request $request){
        
        
        $this->validate($request, [
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png',
            ]
        ]);

        $stylist = $request['stylist'];
        //バリデーションを正常に通過した時の処理
        if ($request->file('file')->isValid([])) {
            $upload_info = Storage::disk('s3')->putFile('/stylists', $request->file('file'), 'public');
            $path = Storage::disk('s3')->url($upload_info);

            //ユーザIDの取得
            $user_id = Auth::id();

            //モデルファイルのクラスからインスタンスを作成し、オブジェクト変数$new_image_dataに格納する
            $stylists = new Stylist;
            $stylists->admin_id = $id;
            $stylists->name = $stylist['name'];
            $stylists->gender = $stylist['gender'];
            
            $stylists->save();
            
            //スタイリストIDの取得
            $stylist_id = Stylist::where("name",$stylist["name"])->where("admin_id",$id)->value("id");
            
            //webpのS3への写真の保存
            Compress::dispatch($path,$user_id,$stylist_id);
            
            
            $name = Admin::query()->where("id",$id)->value('name');
            
            return redirect('/admin/home')->with([
                "name" => $name,
                "salon_id" => $id,
                'flash_message' => '登録が完了しました']);
        }else{
            return redirect('/admin/'.$id.'/register_stylist');
        }
    }
    
    
    

    
}
