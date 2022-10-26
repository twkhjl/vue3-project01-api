<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['']]);
    }
    public function all(){
        $categories = Category::all();
        return response()->json($categories->makeHidden([
            'created_at',
            'updated_at',
        ]));
    }
    public function one($id){
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
            if($request->input('img')==null){
                $request->request->remove('img');
            }
            $validator = Validator::make($request->all(), [

                'name' => ['required','unique:categories'],
                'description' => ['required'],
                // 'img'=>['mimes:jpg,jpeg,png','max:1024'],
                // 'img'=>['image','max:1024','dimensions:max_width=300,max_height=218'],
                // 'img'=>['image','max:1024','dimensions:width=640,height=915'],
                'img' => ['image', 'max:1024'],

                // 'description' => ['required'],

            ], [
                // 自定錯誤訊息
                'name.unique'=>'"'.$request->input('name').'"'.' 已被使用,請改用其他名稱',
                'required' => ':attribute不可空白',
                'image' => ':attribute只能上傳圖片檔案',
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
            if ($request->hasFile('img')) {
                $formField['img'] = $request->file('img')->store('imgs', 'public/imgs/categories/');
            };
            $category->create($formField);

            return response()->json(['result' => 'success']);
        } catch (\Exception $e) {

            return
                response()->json(['server error' => $e->getMessage()]);
        }





        // return redirect(route('listings.index'))->with('message','成功新增一筆資料');
    }
}
