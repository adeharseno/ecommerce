@extends('themes.briquet.layouts.app')
@section('title', 'Shop Coconut Charcoal — Sumatra Briks')
@section('content')
<section class="catalog-hero"><div class="site-container catalog-hero-inner"><div><div class="eyebrow">SUMATRA BRIKS / SHOP</div><h1>Find your<br><em>perfect burn.</em></h1></div><p>Shop coconut shell charcoal briquettes. Add to cart and pay by manual bank transfer.</p></div></section>
<section class="section catalog-body"><div class="site-container">
    <div class="catalog-intro"><div><div class="eyebrow">THE COLLECTION</div><h2>Shop all products</h2></div></div>
    <form class="catalog-filter store-filter" method="GET" action="{{ route('products.index') }}">
        <input class="catalog-search" type="search" name="search" value="{{ request('search') }}" placeholder="Search charcoal products..." aria-label="Search products">
        @if($categories->isNotEmpty())
            <select class="catalog-search" name="category" aria-label="Filter category">
                <option value="">All categories</option>
                @foreach($categories as $cat)<option value="{{ $cat }}" @selected(request('category') === $cat)>{{ ucfirst($cat) }}</option>@endforeach
            </select>
        @endif
        <button type="submit" class="catalog-search-button">Search ↗</button>
        @if(request()->hasAny(['search','category']))<a href="{{ route('products.index') }}" class="store-filter-reset">Reset</a>@endif
    </form>
    @if($products->isEmpty())
        <div class="store-empty-state"><h3>No products available yet.</h3><p>Store products will appear once price and stock are configured by the administrator.</p></div>
    @else
        <div class="product-grid">@foreach($products as $product)@include('themes.briquet.partials.store-product-card', ['product' => $product])@endforeach</div>
        @if($products->hasPages())<div class="store-pagination">{{ $products->links() }}</div>@endif
    @endif
</div></section>
@endsection
