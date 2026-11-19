<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#181c1b">
    <meta name="description" content="Sumatra Briks — premium Indonesian coconut shell charcoal briquettes for shisha, hookah, BBQ and global wholesale supply.">
    <title>@yield('title', 'Sumatra Briks — Premium Coconut Charcoal')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="sumatra-site">
    <div class="announcement-bar"><span class="announcement-dot"></span> INDONESIAN ORIGIN <span class="announcement-divider">/</span> WORLDWIDE SUPPLY <span class="announcement-divider">/</span> CUSTOM PACKAGING AVAILABLE</div>
    <header class="site-header">
        <div class="site-container header-inner">
            <a href="{{ route('home') }}" class="brand" aria-label="Sumatra Briks homepage">
                <span class="brand-mark"><span></span><span></span><span></span></span>
                <span class="brand-copy">SUMATRA<span>BRIKS</span><small>COCONUT CHARCOAL CO.</small></span>
            </a>
            <nav id="site-navigation" class="nav-links" aria-label="Main navigation">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Products</a>
                <a href="{{ route('home') }}#why-us">Why Sumatra</a>
                <a href="{{ route('home') }}#process">Our Process</a>
                <a href="{{ route('home') }}#contact">Contact</a>
            </nav>
            <div class="header-actions">
                <a class="header-cart" href="{{ route('cart.index') }}" aria-label="Open cart">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.5 13.5h12.3l2.1-9.5H6"/></svg>
                    @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
                    @if($cartCount > 0)<span class="cart-count">{{ $cartCount }}</span>@endif
                </a>
                <a class="header-quote" href="{{ route('products.index') }}">Shop Now <span>↗</span></a>
                <button id="menu-toggle" type="button" class="mobile-toggle" aria-controls="site-navigation" aria-expanded="false" aria-label="Toggle menu"><span></span><span></span><span></span></button>
            </div>
        </div>
    </header>
    @if (session('success'))<div class="site-container flash flash-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="site-container flash flash-error">{{ session('error') }}</div>@endif
    <main>@yield('content')</main>
    <footer class="site-footer" id="footer">
        <div class="site-container">
            <div class="footer-top"><div><div class="eyebrow light">SHOP SUMATRA BRIKS</div><h2>Made to burn.<br><em>Ready to shop.</em></h2></div><a class="button button-copper" href="{{ route('products.index') }}">Browse products <span>↗</span></a></div>
            <div class="footer-columns">
                <div class="footer-identity"><a href="{{ route('home') }}" class="footer-brand">SUMATRA<span>BRIKS</span><sup>®</sup></a><p>Thoughtfully made coconut shell charcoal briquettes from Indonesia, created for brands, distributors and buyers around the world.</p><small>MEDAN, NORTH SUMATRA, INDONESIA</small></div>
                <div><h4>EXPLORE</h4><a href="{{ route('home') }}">Home</a><a href="{{ route('products.index') }}">Products</a><a href="{{ route('home') }}#why-us">Why choose us</a><a href="{{ route('home') }}#process">Our process</a></div>
                <div><h4>SUPPORT</h4><a href="{{ route('cart.index') }}">Your cart</a><a href="{{ route('page.show', 'about') }}">About us</a><a href="{{ route('page.show', 'qna') }}">FAQs</a><a href="{{ route('page.show', 'kebijakan-privasi') }}">Privacy policy</a><a href="{{ route('page.show', 'term-and-condition') }}">Terms &amp; conditions</a><a href="{{ route('page.show', 'return-policy') }}">Return policy</a></div>
                <div><h4>GET IN TOUCH</h4><a href="mailto:info@sumatrabriks.com">info@sumatrabriks.com</a><p>Medan, Indonesia</p><a href="{{ route('products.index') }}">Shop charcoal ↗</a></div>
            </div>
            <div class="footer-bottom"><span>© {{ date('Y') }} Sumatra Briks. All rights reserved.</span><span>MADE IN INDONESIA · DESIGNED FOR THE WORLD</span><a href="#top" onclick="window.scrollTo({top:0,behavior:'smooth'});return false">Back to top ↑</a></div>
        </div>
    </footer>
    <script>document.getElementById('menu-toggle')?.addEventListener('click',function(){var n=document.getElementById('site-navigation');var o=n.classList.toggle('is-open');this.setAttribute('aria-expanded',String(o));});document.querySelectorAll('#site-navigation a').forEach(function(a){a.addEventListener('click',function(){document.getElementById('site-navigation').classList.remove('is-open');document.getElementById('menu-toggle')?.setAttribute('aria-expanded','false');});});</script>
    @stack('scripts')
</body>
</html>
