<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\ThemeService;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(private readonly ThemeService $theme) {}

    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return view($this->theme->view('page'), compact('page'));
    }
}
