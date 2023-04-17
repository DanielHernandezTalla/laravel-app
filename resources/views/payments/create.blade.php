@extends('layouts.app')


@section('content')
    <h1>Payment details</h1>

    <h4 class="text-center">Order Total: <strong> {{ $order->total }} </strong></h4>

    <div class="text-center mb-3">
        <form action="{{ route('orders.payments.store', ['order' => $order->id]) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Pay</button>
        </form>
    </div>



@endsection 