<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\file_Image;

class ImageController extends Controller
{
    //
    public function register_stylist()
    {
        return view('admin.register_stylist');
    }
    
    
    public function upload(Request $request){
        $this->validate($request, [
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png',
            ]
        ]);
        
        //バリデーションを正常に通過した時の処理
        if ($request->file('file')->isValid([])) {
            $upload_info = Storage::disk('s3')->putFile('/stylists', $request->file('file'), 'public');
            $path = Storage::disk('s3')->url($upload_info);
            //ユーザIDの取得
            $user_id = Auth::id();
            //モデルファイルのクラスからインスタンスを作成し、オブジェクト変数$new_image_dataに格納する
            
            $new_image_data = file_Image::insert([
                "user_id" => $user_id,
                "path" => $path,
                ]);
            
            return redirect('/home');
        }else{
            return redirect('/upload/image');
        }
    }
    
    public function output()
    {
        
    }

    
}
