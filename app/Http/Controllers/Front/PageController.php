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
        $aliases = [
            'term-and-condition' => 'syarat-ketentuan',
            'terms-and-conditions' => 'syarat-ketentuan',
            'terms-conditions' => 'syarat-ketentuan',
            'terms' => 'syarat-ketentuan',
            'privacy-policy' => 'kebijakan-privasi',
            'privacy' => 'kebijakan-privasi',
            'faq' => 'qna',
            'faqs' => 'qna',
            'tentang-kami' => 'about',
            'return-policy' => 'kebijakan-pengembalian',
            'refund-policy' => 'kebijakan-pengembalian',
            'returns' => 'kebijakan-pengembalian',
            'pengembalian' => 'kebijakan-pengembalian',
        ];

        $targetSlug = $aliases[$slug] ?? $slug;

        $page = Page::where('slug', $slug)
            ->orWhere('slug', $targetSlug)
            ->firstOrFail();

        return view($this->theme->view('page'), compact('page'));
    }
}
