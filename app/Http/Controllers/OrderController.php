<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public $cartService; 

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->middleware('auth');
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
        return DB::transaction(function() use ($request){
            $user = $request->user();
            
            $order = $user->orders()->create([
                'status' => 'pending'
            ]);
    
            $cart = $this->cartService->getFromCookie();
    
            $cartProductsWithQuantity = $cart
                ->products
                ->mapWithKeys(function ($product){
                    
                    $quantity = $product->pivot->quantity;

                    if($product->stock < $quantity){
                        throw ValidationException::withMessages([
                            'product' => "There is not enough   stock for the quality you required of {$product->title}"
                        ]);
                    }

                    $product->decrement('stock', $quantity);
                    $element[$product->id] = ['quantity' => $quantity];

                    return $element;
                });
    
            $order->products()->attach($cartProductsWithQuantity->toArray());
    
            return redirect()->route('orders.payments.create', ['order' => $order]);
        }, 5);
    }
}
