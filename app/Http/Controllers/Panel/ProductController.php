<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\PanelProduct;
use App\Http\Requests\ProductRequest;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;

class ProductController extends Controller
{
    // public function __construct(){
    //     $this->middleware('auth');
    // }
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(){
        // $products = DB::table('products')->get();
        $products = PanelProduct::all();
        
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
    public function show (PanelProduct $product) {
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
        // dd($request->validated());
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
        $product = PanelProduct::create($request->validated());

        foreach($request->images as $image){

            // dd($image);
            $file = $image;
            $file_name = $file->getClientOriginalName();    
            $file_ext = $file->getClientOriginalExtension();
            $fileInfo = pathinfo($file_name);
            $newname = $fileInfo['filename']  . "." . $file_ext;
            $destinationPath = public_path('images/products');
            $file->move($destinationPath, $newname);
            $url = 'images/products/' . $fileInfo['filename'] . "." . $file_ext;
            
            $product->images()->create([
                'path' => $url
            ]);
        }

        // session()->flash('success', "The new product with id {$product->id} was created");

        // return($product);
        // return redirect()->back();
        // return redirect()->action('MainController@index');
        return redirect()
            ->route('products.index')
            ->withSuccess("The new product with id {$product->id} was created");
    }

    public function edit (PanelProduct $product) {
        return view('products.edit')->with([
            'product' => $product
        ]);
    }

    public function update(ProductRequest $request, PanelProduct $product) {

        // dd($request->validated());
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

        if($request->hasFile('images')){

            foreach($product->images as $image){

                $path = storage_path("app/public//{$image->path}");

                File::delete($path);

                $image->delete();
            }


            foreach($request->images as $image){
                $file = $image;
                $file_name = $file->getClientOriginalName();    
                $file_ext = $file->getClientOriginalExtension();
                $fileInfo = pathinfo($file_name);
                $newname = $fileInfo['filename']  . "." . $file_ext;
                $destinationPath = public_path('images/products');
                $file->move($destinationPath, $newname);
                $url = 'images/products/' . $fileInfo['filename'] . "." . $file_ext;
                
                $product->images()->create([
                    'path' => $url
                ]);
            }
        }

        return redirect()->route('products.index')->withSuccess("The new product with id {$product->id} was updated");
    }

    public function destroy (PanelProduct $product) {
        // $product = Product::findOrFail($product);

        $product->delete();

        return redirect()->route('products.index')->withSuccess("The new product with id {$product->id} was deleted");
    }
}
