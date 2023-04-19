<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductCartController extends Controller
{
    public $cartService; 

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $cart = $this->cartService->getFromCookieOrCreate();

        // dd($cart);

        $quantity = $cart->products()
            ->find($product->id)
            ->pivot
            ->quantity ?? 0;

        if($product->stock < $quantity + 1){
            throw ValidationException::withMessages([
                'product' => "There is not enough   stock for the quality you required of {$product->title}"
            ]);
        }

        $cart->products()->syncWithoutDetaching([
            $product->id => ['quantity' => $quantity + 1],
        ]);
        
        // Actualiza la fecha en la base de datos 
        $cart->touch();

        $cookie = $this->cartService->makeCookie($cart);
        // $cookie = cookie('cart', $cart->id, 7 * 24 * 60);
        // \Cookie::make('cuco', 'cucurucu', 60 * 24 * 365);
        // cookie('cart', $cart->id, 7 * 24 * 60);
        // cookie()->make('cart', $cart->id, 7 * 24 * 60);

        return redirect()->back()->withCookie($cookie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Cart $cart)
    {
        $cart->products()->detach($product->id);

        // Actualiza la fecha en la base de datos 
        $cart->touch();

        $cookie = $this->cartService->makeCookie($cart);

        return redirect()->back()->withCookie($cookie);
    }

}
