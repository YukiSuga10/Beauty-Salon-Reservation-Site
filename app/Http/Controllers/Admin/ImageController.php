<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\file_Image;
use App\Admin;
use App\Stylist;


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
            
            
            
            
            $new_image_data = file_Image::insert([
                "admin_id" => $id,
                "stylist_id" => $stylists->id,
                "path" => $path,
                ]);
            
            
            return redirect('/admin/home')->with([
                "salon_id" => $id,
                'flash_message' => '登録が完了しました']);
        }else{
            return redirect('/admin/{{id}}/register_stylist');
        }
    }
    
    
    

    
}
