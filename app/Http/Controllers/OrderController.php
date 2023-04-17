<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    public $cartService; 

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cart = $this->cartService->getFromCookie();
        
        if(!isset($cart) || $cart->products->isEmpty()){
            return redirect()->back()->withErrors("Your cart is empty!");
        }

        // dd($cart->products);
        return view('orders.create')
            ->with(['cart' => $cart]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }
}
