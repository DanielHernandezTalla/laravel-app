@extends('layouts.app')


@section('content')
    <h1>Your cart</h1>
    
    @if (!isset($cart) || $cart->products->isEmpty())
        <div class="alert alert-warning">
            No products yet!
        </div>
    @else 
        <div class="row">
            @foreach ($cart->products as $product)
            <div class="col-3">
                @include('components.product-card')
            </div>
            @endforeach
        </div>
    @endif


@endsection