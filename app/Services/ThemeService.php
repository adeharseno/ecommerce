<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

/**
 * Resolves the active theme name from configuration.
 *
 * Theme config is read from config/theme.php → 'active'.
 * Valid values: briquet | mua | coffee | furniture
 */
class ThemeService
{
    /** @var array<string> */
    private const VALID_THEMES = ['briquet', 'mua', 'coffee', 'furniture'];

    public function active(): string
    {
        $theme = Config::string('theme.active', 'briquet');

        if (! in_array($theme, self::VALID_THEMES, strict: true)) {
            return 'briquet';
        }

        return $theme;
    }

    /**
     * Resolve a theme-namespaced view path.
     *
     * E.g. view('products.index') → 'themes.briquet.products.index'
     */
    public function view(string $view): string
    {
        return 'themes.'.$this->active().'.'.$view;
    }
}
