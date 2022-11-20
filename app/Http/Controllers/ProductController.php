<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public $IMG_FILE_PATH = 'imgs/categories/';

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['']]);
        $this->middleware('jwtauth', ['except' => ['login', 'refresh']]);
    }
    public function all()
    {
        $products = Product::all();
        // $products = Product::all();
        return response()->json($products->makeHidden([
            'created_at',
            'updated_at',
        ]));
    }
    public function paginate()
    {
        $products = Product::paginate(20);

        return response()->json([
            $products
        ]);

        return response()->json($products->makeHidden([
            'created_at',
            'updated_at',
        ]));
    }
}
