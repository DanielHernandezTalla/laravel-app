<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class MainController extends Controller
{
    public function index(){
        $products = Product::available()->get();

        return view('welcome')->with([
            'products' => $products
        ]);
    }
}
