<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Image;
use Storage;
use Illuminate\Http\File;
use App\SalonImage;

class Compress_salonImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $url,$id;
    
    public function __construct($url,$id)
    {
        $this->url = $url;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //AWSなどのURL
        $thumbnailUrl = $this->url;

        //画像の初期化
        $img = Image::make($thumbnailUrl);
        $img->limitColors(null);

        //一時ファイルを置く場所を定義する
        $file_path_image = '/public/thumbnai';
        //絶対パスでapp/temp/thumbnail/のディレクトリを定義
        $file_path = storage_path('app'.$file_path_image);
        //保存する先のS3バケットを定義
        $disk = Storage::disk('s3');
        
        //画像をwebpでLaravelのStorageに一時保存
        $img->encode("webp");
        $img->save($file_path);
        
        //S3に保存
        $path = $disk->putFile('/salonImage',new File($file_path),'public');
        
        //あとは保存したいDBに保存するだけ
        $new_image_data = SalonImage::insert([
                "admin_id" => $this->id,
                "path" => $path,
                ]);

        //一時ファイルの削除
        Storage::disk('local')->delete($file_path_image);
    }
}
