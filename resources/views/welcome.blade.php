@extends('layouts.app')


@section('content')
    <h1>Welcome</h1>
    
    @if (@empty($products))
        <div class="alert alert-danger">
            No products yet!
        </div>
    @else 
        <div class="row">
            {{-- @dump($products) --}}
            
            @foreach ($products as $product)
            <div class="col-3">
                @include('components.product-card')
            </div>
            @endforeach

            {{-- @dump($products) --}}
            {{-- Leyendo los log almacenados --}}
            {{-- @dd(\DB::getQueryLog()) --}}
        </div>
    @endif


@endsection