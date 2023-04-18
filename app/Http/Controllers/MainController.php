<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class MainController extends Controller
{
    public function index(){

        // Forma de guardar los datos en un log
        // \DB::connection()->enableQueryLog();

        $products = Product::without('inages')->get();

        return view('welcome')->with([
            'products' => $products
        ]);
    }
}
