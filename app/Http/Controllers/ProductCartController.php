<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCartController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $cart = $this->getFromCookieOrCreate($request);

        // dd($cart);

        $quantity = $cart->products()
            ->find($product->id)
            ->pivot
            ->quantity ?? 0;

        $cart->products()->syncWithoutDetaching([
            $product->id => ['quantity' => $quantity + 1],
        ]);
        
        $cookie = cookie('cart', $cart->id, 7 * 24 * 60);
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
        //
    }

    public function getFromCookieOrCreate($request){
        $cartId = $request->cookie('cart');

        $cart = Cart::find($cartId);

        return $cart ?? Cart::create();
    }
}
