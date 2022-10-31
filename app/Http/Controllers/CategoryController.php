<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Rules\base64_image;
use App\Rules\base64_max;
use App\Rules\base64_dimension;


class CategoryController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['']]);
        $this->middleware('jwtauth', ['except' => ['login', 'refresh']]);
    }
    public function all()
    {
        $categories = Category::all();
        return response()->json($categories->makeHidden([
            'created_at',
            'updated_at',
        ]));
    }
    public function show($id)
    {
        $category = Category::find($id);
        return response()->json($category->makeHidden([
            'created_at',
            'updated_at',
        ]));
    }
    public function store(Request $request)
    {
// return response()->json($request);


        try {
            // 驗證表單
            if ($request->input('img') == null) {
                $request->request->remove('img');
            }
            $validator = Validator::make($request->all(), [

                'name' => ['required', 'unique:categories'],
                'description' => ['required'],
                // 'img'=>['mimes:jpg,jpeg,png','max:1024'],
                // 'img'=>['image','max:1024','dimensions:max_width=300,max_height=218'],
                // 'img'=>['image','max:1024','dimensions:width=640,height=915'],
                // 'img' => ['image', 'max:1024'],
                'img' => [new base64_image, new base64_max(0.5),new base64_dimension(640,640)],
                // 'img' => [new base64_image, new base64_max(0.5)],


                // 'description' => ['required'],

            ], [
                // 自定錯誤訊息
                'name.unique' => '"' . $request->input('name') . '"' . ' 已被使用,請改用其他名稱',
                'required' => ':attribute不可空白',
                'img.max' => ':attribute最大不得超過1MB',
            ], [
                // 自定欄位在錯誤訊息中的顯示名稱
                'name' => '分類名稱',
                'img' => '分類圖片',
                'description' => '分類描述',
            ]);



            if ($validator->fails()) {

                //只想回傳json
                return response()->json(['errors' => $validator->errors()]);
            };


            // 取得表單輸入值
            $category = new Category();
            $fillable = collect($category->getFillable())->toArray();
            $formField = $request->only($fillable);


            // 處理上傳檔案

            // https://laracasts.com/discuss/channels/laravel/laravel-file-storage-how-to-store-decoded-base64-image
            $image_64 = $request->input('img');
            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
            $image = str_replace($replace, '', $image_64);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(10) . '.' . $extension;

            Storage::disk('public')->put($imageName, base64_decode($image));

            // $formField['img'] = $request->file('img')->store('imgs', 'public/imgs/categories/');
            // $category->create($formField);

            return response()->json(['result' => 'success','path'=>Storage::url($imageName)]);
        } catch (\Exception $e) {

            return
                response()->json(['server error' => $e->getMessage()]);
        }





        // return redirect(route('listings.index'))->with('message','成功新增一筆資料');
    }
}
