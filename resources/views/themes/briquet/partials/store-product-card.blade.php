{{-- Cards must point to real database products. No simulated purchases. --}}
<article class="product-card store-card" id="product-{{ $product->slug }}">
    <a class="product-image" href="{{ route('products.show', $product->slug) }}" aria-label="View {{ $product->name }}">
        <span class="product-tag">{{ $product->category ?: 'COCONUT CHARCOAL' }}</span>
        <img loading="lazy" src="{{ $product->getFirstImageUrl() }}" alt="{{ $product->name }}">
        @if ((int) $product->stock <= 0)<span class="store-stock-overlay">Out of stock</span>@endif
        <span class="product-arrow">↗</span>
    </a>
    <div class="product-details">
        <span class="product-number">SUMATRA BRIKS / SHOP</span>
        <h3><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>
        <p class="store-price">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
        @if ((int) $product->stock > 0)
            <form method="POST" action="{{ route('cart.add', $product) }}" class="store-add-form">
                @csrf
                <input type="hidden" name="quantity" value="1">
                <button class="store-buy-button" type="submit">+ Add to Cart <span>↗</span></button>
            </form>
        @else
            <span class="store-soldout">Currently unavailable</span>
        @endif
    </div>
</article>
