<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartQuantityInputTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_renders_editable_number_input_for_quantity(): void
    {
        $product = Product::create([
            'name' => 'Coconut Charcoal C26',
            'slug' => 'coconut-charcoal-c26',
            'description' => 'Test charcoal',
            'price' => 37000,
            'stock' => 100,
            'images' => ['c26.png'],
            'category' => 'Hookah',
            'is_active' => true,
        ]);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => 37000,
                    'image' => '/c26.png',
                    'quantity' => 20,
                ],
            ],
        ]);

        $response = $this->get('/cart');

        $response->assertStatus(200);
        $response->assertSee('name="quantity"', false);
        $response->assertSee('type="number"', false);
        $response->assertSee('value="20"', false);
        $response->assertSee('Rp 740.000');
    }

    public function test_user_can_type_and_update_arbitrary_quantity(): void
    {
        $product = Product::create([
            'name' => 'Coconut Charcoal C26',
            'slug' => 'coconut-charcoal-c26',
            'description' => 'Test charcoal',
            'price' => 37000,
            'stock' => 100,
            'images' => ['c26.png'],
            'category' => 'Hookah',
            'is_active' => true,
        ]);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => 37000,
                    'image' => '/c26.png',
                    'quantity' => 1,
                ],
            ],
        ]);

        // User types 25 into the input field
        $response = $this->patch('/cart/'.$product->id, [
            'quantity' => 25,
        ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHas('success', 'Keranjang berhasil diperbarui.');

        // Verify session cart has quantity 25
        $cart = session('cart');
        $this->assertEquals(25, $cart[$product->id]['quantity']);

        // Verify cart page displays updated subtotal (37,000 * 25 = 925,000)
        $cartPage = $this->get('/cart');
        $cartPage->assertSee('value="25"', false);
        $cartPage->assertSee('Rp 925.000');
    }
}
