<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;

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
    public function create(){
        return response()->json(request());
    }
}
