<div class="card">
    <img class="card-img-top" src="{{ asset($product->images->first()->path) }}" alt="{{ $product->price }}" height="500px">
    <div class="card-body">
        <h4 class="text-right"><strong> ${{ $product->price }} </strong></h4>
        <h5 class="card-title"> {{ $product->title }} </h5>
        <p class="card-text"> {{ $product->description }} </p>
        <p class="card-text"> <strong> {{ $product->stock }} left </strong> </p>
        
        @if(isset($cart))
            <form action="{{ route('products.carts.destroy', ['cart' => $cart->id, 'product' => $product->id]) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-warning">Remove from cart</button>
            </form>
        @else
            <form action="{{ route('products.carts.store', ['product' => $product->id]) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">Add to cart</button>
            </form>
        @endif
    </div>
</div>