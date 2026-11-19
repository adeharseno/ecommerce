<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Sample storefront data ONLY. Never execute on production.
 * Existing products are never updated or deleted.
 */
class SumatraDemoProductsSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('Demo products are restricted to local/testing.');
        }

        if (! Schema::hasTable('products')) {
            throw new RuntimeException('Table products was not found. Run your existing project migrations first.');
        }

        $columns = Schema::getColumnListing('products');
        foreach (['name', 'slug', 'price', 'stock'] as $required) {
            if (! in_array($required, $columns, true)) {
                throw new RuntimeException("Expected products.{$required} was not found. Check actual database schema.");
            }
        }

        $products = [
            ['C25', 'Coconut Charcoal C25', 'SHISHA / HOOKAH', 35000, 100, '25 × 25 × 25 mm coconut-shell charcoal cubes. Concept product for development.'],
            ['C26', 'Coconut Charcoal C26', 'SHISHA / HOOKAH', 37000, 100, '26 × 26 × 26 mm coconut-shell charcoal cubes. Concept product for development.'],
            ['C27', 'Coconut Charcoal C27', 'SHISHA / HOOKAH', 39000, 100, '27 × 27 × 27 mm coconut-shell charcoal cubes. Concept product for development.'],
            ['C28', 'Coconut Charcoal C28', 'SHISHA / HOOKAH', 41000, 100, '28 × 28 × 28 mm coconut-shell charcoal cubes. Concept product for development.'],
            ['HEXA', 'Hexagonal Charcoal', 'BBQ / GRILLING', 45000, 100, 'Hexagonal charcoal briquettes. Concept product for development.'],
            ['PILLOW', 'Pillow Charcoal', 'BBQ / GRILLING', 43000, 100, 'Pillow-shaped charcoal briquettes. Concept product for development.'],
        ];

        foreach ($products as [$code, $name, $category, $price, $stock, $description]) {
            $slug = Str::slug($name);
            // Never overwrite real products with the same name or slug.
            if (DB::table('products')->where('slug', $slug)->orWhere('name', $name)->exists()) {
                $this->command?->warn("Skipped existing product: {$name}");

                continue;
            }

            $source = public_path('images/sumatra/'.strtolower($code).'.png');
            if (! is_file($source)) {
                throw new RuntimeException("Missing product asset: {$source}. Install the patch assets first.");
            }

            $imagePath = 'products/sumatra-demo-'.strtolower($code).'.png';
            Storage::disk('public')->put($imagePath, file_get_contents($source));

            $data = ['name' => $name, 'slug' => $slug, 'price' => $price, 'stock' => $stock];
            $optional = [
                'category' => $category,
                'description' => $description,
                'is_active' => true,
                'sku' => 'SB-DEMO-'.$code,
                'images' => json_encode([$imagePath], JSON_UNESCAPED_SLASHES),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            foreach ($optional as $key => $value) {
                if (in_array($key, $columns, true)) {
                    $data[$key] = $value;
                }
            }

            DB::table('products')->insert($data);
            $this->command?->info("Created demo product: {$name}");
        }
    }
}
