<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ThemeService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly ThemeService $theme) {}

    public function index(): View
    {
        $products = Product::active()
            ->latest()
            ->take(8)
            ->get();

        return view($this->theme->view('home'), compact('products'));
    }
}
