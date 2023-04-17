@extends('layouts.app')


@section('content')
    <h1>Order details</h1>

    <h4 class="text-center">Grand Total: <strong> {{ $cart->total }} </strong></h4>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="thead-light">
                <tr>
                    <th>Producto</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart->products as $product)
                    <tr>
                        <td>
                            <img src="{{ asset($product->images->first()->path) }}" alt="{{ $product->title }}" width="75px">
                            {{ $product->title }} 
                        </td>
                        <td> {{ $product->price }} </td>
                        <td> {{ $product->pivot->quantity }} </td>
                        <td> 
                            <strong>
                                ${{ $product->total }}
                            </strong>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection