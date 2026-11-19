<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImageConsistencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_product_listing_and_detail_use_consistent_primary_image(): void
    {
        $productC25 = Product::create([
            'name' => 'Coconut Charcoal C25',
            'slug' => 'coconut-charcoal-c25',
            'description' => '25mm cube charcoal',
            'price' => 35000,
            'stock' => 100,
            'images' => ['products/new-packaging-c25.png'],
            'category' => 'SHISHA / HOOKAH',
            'is_active' => true,
        ]);

        $productC26 = Product::create([
            'name' => 'Coconut Charcoal C26',
            'slug' => 'coconut-charcoal-c26',
            'description' => '26mm cube charcoal',
            'price' => 37000,
            'stock' => 100,
            'images' => ['products/new-packaging-c26.png'],
            'category' => 'SHISHA / HOOKAH',
            'is_active' => true,
        ]);

        $expectedUrlC25 = $productC25->getFirstImageUrl();
        $expectedUrlC26 = $productC26->getFirstImageUrl();

        $this->assertStringContainsString('products/new-packaging-c25.png', $expectedUrlC25);
        $this->assertStringContainsString('?v='.$productC25->updated_at->timestamp, $expectedUrlC25);

        // 1. Check Homepage
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee($expectedUrlC25, false);
        $homeResponse->assertSee($expectedUrlC26, false);
        $homeResponse->assertDontSee('images/sumatra/c25.png', false);
        $homeResponse->assertDontSee('images/sumatra/c26.png', false);

        // 2. Check Listing Page
        $listingResponse = $this->get('/products');
        $listingResponse->assertStatus(200);
        $listingResponse->assertSee($expectedUrlC25, false);
        $listingResponse->assertSee($expectedUrlC26, false);
        $listingResponse->assertDontSee('images/sumatra/c25.png', false);

        // 3. Check Detail Page for C25
        $detailResponse = $this->get('/products/'.$productC25->slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($expectedUrlC25, false);

        // 4. Check Related Products on C25 detail page (should show C26 with its new packaging)
        $detailResponse->assertSee($expectedUrlC26, false);
    }

    public function test_updating_product_image_in_cms_updates_url_and_versioning_across_all_pages(): void
    {
        $product = Product::create([
            'name' => 'Pillow Charcoal',
            'slug' => 'pillow-charcoal',
            'description' => 'Pillow briquettes',
            'price' => 43000,
            'stock' => 50,
            'images' => ['products/pillow-v1.png'],
            'category' => 'BBQ / GRILLING',
            'is_active' => true,
        ]);

        $initialUrl = $product->getFirstImageUrl();
        $this->assertStringContainsString('products/pillow-v1.png', $initialUrl);

        // Simulate CMS updating the primary image
        $product->update([
            'images' => ['products/pillow-v2-new-packaging.png'],
            'updated_at' => now()->addMinutes(5),
        ]);

        $updatedProduct = $product->fresh();
        $newUrl = $updatedProduct->getFirstImageUrl();

        $this->assertStringContainsString('products/pillow-v2-new-packaging.png', $newUrl);
        $this->assertNotEquals($initialUrl, $newUrl);

        // Homepage reflection
        $home = $this->get('/');
        $home->assertSee($newUrl, false);
        $home->assertDontSee('images/sumatra/pillow.png', false);

        // Listing reflection
        $listing = $this->get('/products');
        $listing->assertSee($newUrl, false);

        // Detail reflection
        $detail = $this->get('/products/'.$product->slug);
        $detail->assertSee($newUrl, false);
    }

    public function test_product_without_image_uses_fallback_gracefully(): void
    {
        $productWithoutImage = Product::create([
            'name' => 'Custom Charcoal Product',
            'slug' => 'custom-charcoal-product',
            'description' => 'A product without any uploaded image',
            'price' => 50000,
            'stock' => 10,
            'images' => [],
            'category' => 'GENERAL',
            'is_active' => true,
        ]);

        $fallbackUrl = $productWithoutImage->getFirstImageUrl();
        $this->assertNotEmpty($fallbackUrl);
        $this->assertStringContainsString('placeholder', $fallbackUrl);

        $listing = $this->get('/products');
        $listing->assertStatus(200);
        $listing->assertSee($fallbackUrl, false);
    }
}
