<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->except(['index']);
    }
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(){
        // $products = DB::table('products')->get();
        $products = Product::all();
        
        // dd($products);
        
        // return $products;
        return view('products.index')->with([
            'products' => $products
        ]);
    }
    
    public function create() {

        return view('products.create');
    }
    
    // Se trae el dato directamente de la base de datos cuando recibes el parametro con el nombre del modelo
    public function show (Product $product) {
        // $product = DB::table('products')->where('id', $product)->first();
        // $product = DB::table('products')->find($product);
        // $product = Product::findOrFail($product);

        // dd($product);

        // return $product;
        return view('products.show')->with([
            'product' => $product
        ]);
    }

    public function store (ProductRequest $request) {
    // public function store () {
        // $product = Product::create([
        //     'title' => request()->title,
        //     'description' => request()->description,
        //     'price' => request()->price,
        //     'stock' => request()->stock,
        //     'status' => request()->status
        // ]);

        // $rules = [
        //     'title' => ['required', 'max:255'],
        //     'description' => ['required', 'max:1000'],
        //     'price' => ['required', 'min:1'],
        //     'stock' => ['required', 'min:0'],
        //     'status' => ['required', 'in:available,unavailable']
        // ];

        // request()->validate($rules);

        // if($request()->status == 'available' && $request()->stock == 0){
        //     // session()->put('error', 'If available mush have stock');
        //     // session()->flash('error', 'If available mush have stock');
        //     return redirect()
        //         ->back()
        //         ->withInput($request()->all())
        //         ->withErrors('If available mush have stock');
        // }

        // session()->forget('error');

        // dd($request);
        // $product = Product::create($request()->all());
        $product = Product::create($request->validated());

        // session()->flash('success', "The new product with id {$product->id} was created");

        // return($product);
        // return redirect()->back();
        // return redirect()->action('MainController@index');
        return redirect()
            ->route('products.index')
            ->withSuccess("The new product with id {$product->id} was created");
    }

    public function edit (Product $product) {
        return view('products.edit')->with([
            'product' => $product
        ]);
    }

    public function update(ProductRequest $request, Product $product) {

        // $rules = [
        //     'title' => ['required', 'max:255'],
        //     'description' => ['required', 'max:1000'],
        //     'price' => ['required', 'min:1'],
        //     'stock' => ['required', 'min:0'],
        //     'status' => ['required', 'in:available,unavailable']
        // ];

        // request()->validate($rules);

        // $product = Product::findOrFail($product);

        $product->update($request->validated());

        return redirect()->route('products.index')->withSuccess("The new product with id {$product->id} was updated");
    }

    public function destroy (Product $product) {
        // $product = Product::findOrFail($product);

        $product->delete();

        return redirect()->route('products.index')->withSuccess("The new product with id {$product->id} was deleted");
    }
}
