<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Cookie;

class CartService{

    protected $cookieName;
    protected $cookieExpiration;

    public function __construct()
    {
        $this->cookieName = config('cart.cookie.name');
        $this->cookieExpiration = config('cart.cookie.expiration');
    }

    public function getFromCookie()
    {
        $cartId = Cookie::get($this->cookieName);

        $cart = Cart::find($cartId);

        return $cart;
    }

    public function getFromCookieOrCreate(){
        // $cartId = Cookie::get($this->cookieName);

        // dump($cartId);
        
        $cart = $this->getFromCookie();
        // dd($cart);
        
        return $cart ?? Cart::create();
    }

    public function makeCookie(Cart $cart)
    {
        // return Cookie::make('cart', $cart->id, 7 * 24 * 60);
        return Cookie::make($this->cookieName, $cart->id, $this->cookieExpiration);
    }

    public function countProducts()
    {
        $cart = $this->getFromCookie();

        if($cart != null){
            return $cart->products->pluck('pivot.quantity')->sum();
        }

        return 0;
    }
}