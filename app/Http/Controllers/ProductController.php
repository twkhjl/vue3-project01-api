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
        $perPage = 20;
        $columns = ['*'];
        $pageName = 'page';
        $pageNumber = 1;

        if (request()->perPage) {
            $perPage = request()->perPage;
        }
        if (request()->$pageName) {
            $pageNumber = request()->pageNumber;
        }

        $products = Product::paginate($perPage,$columns,$pageName,$pageNumber);

        return response()->json([
            $products
        ]);

        return response()->json($products->makeHidden([
            'created_at',
            'updated_at',
        ]));
    }
}
