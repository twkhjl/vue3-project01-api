<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TttController extends Controller
{
    public function test(){
        return response()->json('ttt');
    }
}
