<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class FileHelper
{
    public static function get_base64_file_data($file_64)
    {

        // handle base64 file
        // https://laracasts.com/discuss/channels/laravel/laravel-file-storage-how-to-store-decoded-base64-file
        $extension = explode('/', explode(':', substr($file_64, 0, strpos($file_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($file_64, 0, strpos($file_64, ',') + 1);
        $file = str_replace($replace, '', $file_64);
        $file = str_replace(' ', '+', $file);
        $fileName = Str::random(10) . '.' . $extension;

        $size = FileHelper::get_base64_file_size($file_64, 'mb');
        return [
            'data' => $file,
            'extension' => $extension,
            'size' => $size,
        ];
    }
    public static function get_random_file_name($extension, $number = 10)
    {
        return Str::random($number) . '.' . $extension;
    }
    public static function store_base64_data($path,$data,$disk=null)
    {
        $img_store_path=$path;
        $base64_data=$data;
        if($disk==null){
            $disk='public';
        }

        Storage::disk('public')->put($img_store_path, base64_decode($base64_data));
        $img_url = Storage::url($img_store_path);
        return $img_url;
    }

    public static function get_base64_image_dimension($file_64){
        // https://stackoverflow.com/questions/48828055/how-to-get-height-and-width-from-base64-encoded-image
        $binary = \base64_decode(\explode(',', $file_64)[1]);

        $data = \getimagesizefromstring($binary);
        $data = $data['3'];
        $data = str_replace('"','',$data);
        $data = str_replace(' ',',',$data);

        // https://stackoverflow.com/questions/4923951/php-split-string-in-key-value-pairs
        preg_match_all("/([^,= ]+)=([^,= ]+)/", $data, $r);
        $data = array_combine($r[1], $r[2]);

        return $data;
    }


    // https://stackoverflow.com/questions/12658661/validating-base64-encoded-images
    public static function get_base64_file_size($file_64, $unit = 'mb')
    { //return memory size in B, KB, MB
        try {
            $size_in_bytes = (int) (strlen(rtrim($file_64, '=')) * 3 / 4);
            $size_in_kb    = $size_in_bytes / 1024;
            $size_in_mb    = $size_in_kb / 1024;

            $unitArr = [
                'byte' => $size_in_bytes,
                'kb' => $size_in_kb,
                'mb' => $size_in_mb,
            ];

            return $unitArr[$unit];
        } catch (\Exception $e) {
            return $e;
        }
    }
}
