<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_redirects_if_cart_is_empty(): void
    {
        $response = $this->get('/checkout');

        $response->assertRedirect('/cart');
        $response->assertSessionHas('error', 'Keranjang Anda kosong.');
    }

    public function test_checkout_page_renders_with_cart_items_and_shows_bank_info(): void
    {
        $product = Product::create([
            'name' => 'Coconut Briquette C25',
            'slug' => 'coconut-briquette-c25',
            'description' => 'Premium charcoal',
            'price' => 35000,
            'stock' => 50,
            'images' => ['products/c25.png'],
            'category' => 'Briquette',
            'is_active' => true,
        ]);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => 35000,
                    'image' => '/storage/products/c25.png',
                    'quantity' => 2,
                ],
            ],
        ]);

        $response = $this->get('/checkout');

        $response->assertStatus(200);
        $response->assertSee('Coconut Briquette C25');
        $response->assertSee('Transfer Bank BRI');
        $response->assertSee('AKRATAMA SADAWIRA GRUP');
        $response->assertSee('Dikonfirmasi Admin');
        $response->assertSee('Buat Pesanan');
    }

    public function test_checkout_validation_errors_for_required_fields(): void
    {
        $product = Product::create([
            'name' => 'Coconut Briquette C25',
            'slug' => 'coconut-briquette-c25',
            'description' => 'Premium charcoal',
            'price' => 35000,
            'stock' => 50,
            'images' => [],
            'category' => 'Briquette',
            'is_active' => true,
        ]);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => 35000,
                    'image' => '/placeholder.png',
                    'quantity' => 1,
                ],
            ],
        ]);

        $response = $this->post('/checkout', [
            'customer_name' => '',
            'customer_phone' => '',
            'shipping_address' => '',
        ]);

        $response->assertSessionHasErrors(['customer_name', 'customer_phone', 'shipping_address']);
        $this->assertEquals(0, Transaction::count());
    }

    public function test_checkout_fails_gracefully_when_stock_is_insufficient_and_preserves_cart(): void
    {
        $product = Product::create([
            'name' => 'Limited Briquette',
            'slug' => 'limited-briquette',
            'description' => 'Low stock item',
            'price' => 50000,
            'stock' => 1, // Only 1 in stock
            'images' => [],
            'category' => 'Briquette',
            'is_active' => true,
        ]);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => 50000,
                    'image' => '/placeholder.png',
                    'quantity' => 5, // Customer asks for 5
                ],
            ],
        ]);

        $response = $this->post('/checkout', [
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Merdeka No. 10 Jakarta',
        ]);

        $response->assertRedirect('/checkout');
        $response->assertSessionHas('error');
        // Product stock remains untouched
        $this->assertEquals(1, $product->fresh()->stock);
        // No transaction created
        $this->assertEquals(0, Transaction::count());
        // Cart session is preserved
        $this->assertNotEmpty(session('cart'));
    }

    public function test_checkout_successfully_creates_order_with_single_product_and_deducts_stock(): void
    {
        $product = Product::create([
            'name' => 'Charcoal C25',
            'slug' => 'charcoal-c25',
            'description' => 'Test charcoal',
            'price' => 40000,
            'stock' => 20,
            'images' => ['c25.png'],
            'category' => 'Hookah',
            'is_active' => true,
        ]);

        $this->withSession([
            'cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => 40000,
                    'image' => '/c25.png',
                    'quantity' => 3,
                ],
            ],
        ]);

        $response = $this->post('/checkout', [
            'customer_name' => 'Ahmad Dani',
            'customer_phone' => '081298765432',
            'shipping_address' => 'Jl. Sudirman No. 45, Medan',
        ]);

        $transaction = Transaction::first();
        $this->assertNotNull($transaction);
        $this->assertEquals('pending', $transaction->status);
        $this->assertEquals(120000, (float) $transaction->total_amount);
        $this->assertEquals('Ahmad Dani', $transaction->customer_name);
        $this->assertEquals('BRI', $transaction->bank_name);
        $this->assertEquals('AKRATAMA SADAWIRA GRUP', $transaction->account_holder);

        // Stock was reduced from 20 to 17
        $this->assertEquals(17, $product->fresh()->stock);

        // Cart is cleared after successful transaction
        $this->assertEmpty(session('cart'));

        // Redirects to success page
        $response->assertRedirect('/checkout/success/'.$transaction->invoice_number);
    }

    public function test_checkout_successfully_creates_order_with_multiple_products(): void
    {
        $p1 = Product::create([
            'name' => 'Charcoal C25',
            'slug' => 'charcoal-c25',
            'description' => 'Desc 1',
            'price' => 30000,
            'stock' => 10,
            'is_active' => true,
        ]);

        $p2 = Product::create([
            'name' => 'Charcoal Hexagonal',
            'slug' => 'charcoal-hexagonal',
            'description' => 'Desc 2',
            'price' => 45000,
            'stock' => 15,
            'is_active' => true,
        ]);

        $this->withSession([
            'cart' => [
                $p1->id => [
                    'id' => $p1->id,
                    'name' => $p1->name,
                    'slug' => $p1->slug,
                    'price' => 30000,
                    'image' => '/p1.png',
                    'quantity' => 2,
                ],
                $p2->id => [
                    'id' => $p2->id,
                    'name' => $p2->name,
                    'slug' => $p2->slug,
                    'price' => 45000,
                    'image' => '/p2.png',
                    'quantity' => 1,
                ],
            ],
        ]);

        $response = $this->post('/checkout', [
            'customer_name' => 'Rina Wijaya',
            'customer_phone' => '087811223344',
            'shipping_address' => 'Jl. Gatot Subroto No. 12, Bandung',
        ]);

        $transaction = Transaction::first();
        $this->assertNotNull($transaction);
        // Total = (30000 * 2) + (45000 * 1) = 105000
        $this->assertEquals(105000, (float) $transaction->total_amount);
        $this->assertEquals(2, $transaction->items()->count());

        $this->assertEquals(8, $p1->fresh()->stock);
        $this->assertEquals(14, $p2->fresh()->stock);

        $response->assertRedirect('/checkout/success/'.$transaction->invoice_number);
    }

    public function test_success_page_renders_official_bri_details_and_copy_buttons(): void
    {
        $transaction = Transaction::create([
            'invoice_number' => 'INV/20261119/0001',
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '+6281234567890',
            'shipping_address' => 'Jl. Diponegoro No. 8, Surabaya',
            'total_amount' => 70000,
            'status' => 'pending',
            'bank_name' => 'BRI',
            'account_holder' => 'AKRATAMA SADAWIRA GRUP',
        ]);

        // Place session authorization (as when customer just completed checkout)
        $this->withSession([
            'order_access_'.$transaction->invoice_number => true,
        ]);

        $response = $this->get('/checkout/success/'.$transaction->invoice_number);

        $response->assertStatus(200);
        $response->assertSee('INV/20261119/0001');
        $response->assertSee('Bank BRI');
        $response->assertSee('20601010804563');
        $response->assertSee('AKRATAMA SADAWIRA GRUP');
        $response->assertSee('Salin Nomor Rekening');
        $response->assertSee('Salin Nominal Transfer');
        $response->assertSee('Rp 70.000');
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Bank transfer details are not configured yet');
        $response->assertDontSee('Pembayaran transfer bank belum tersedia');
    }

    public function test_success_page_masks_customer_pii_for_unauthorized_visitors(): void
    {
        $transaction = Transaction::create([
            'invoice_number' => 'INV/20261119/0002',
            'customer_name' => 'Siti Rahmawati',
            'customer_phone' => '+6281234567890',
            'shipping_address' => 'Jl. Melati Indah No. 5, Yogyakarta',
            'total_amount' => 150000,
            'status' => 'pending',
            'bank_name' => 'BRI',
            'account_holder' => 'AKRATAMA SADAWIRA GRUP',
        ]);

        // Stranger visits without session authorization
        $response = $this->get('/checkout/success/'.$transaction->invoice_number);

        $response->assertStatus(200);
        // Customer address is masked
        $response->assertSee('Alamat disamarkan demi privasi pelanggan');
        $response->assertDontSee('Jl. Melati Indah No. 5, Yogyakarta');
        // Phone number is masked
        $response->assertDontSee('+6281234567890');
        // Verification form is shown
        $response->assertSee('verify_phone');
    }

    public function test_success_page_allows_customer_phone_verification_to_unlock_details(): void
    {
        $transaction = Transaction::create([
            'invoice_number' => 'INV/20261119/0003',
            'customer_name' => 'Siti Rahmawati',
            'customer_phone' => '+6281234567890',
            'shipping_address' => 'Jl. Melati Indah No. 5, Yogyakarta',
            'total_amount' => 150000,
            'status' => 'pending',
            'bank_name' => 'BRI',
            'account_holder' => 'AKRATAMA SADAWIRA GRUP',
        ]);

        // Stranger verifies with matching phone number
        $response = $this->get('/checkout/success/'.$transaction->invoice_number.'?verify_phone=081234567890');

        $response->assertStatus(200);
        $response->assertSee('Jl. Melati Indah No. 5, Yogyakarta');
        $response->assertSessionHas('order_access_'.$transaction->invoice_number, true);
    }

    public function test_upload_payment_proof_keeps_status_pending_and_saves_file(): void
    {
        Storage::fake('public');

        $transaction = Transaction::create([
            'invoice_number' => 'INV/20261119/0004',
            'customer_name' => 'Hendro',
            'customer_phone' => '+6281122334455',
            'shipping_address' => 'Jl. Sudirman No. 1, Jakarta',
            'total_amount' => 80000,
            'status' => 'pending',
            'bank_name' => 'BRI',
            'account_holder' => 'AKRATAMA SADAWIRA GRUP',
        ]);

        $file = UploadedFile::fake()->create('transfer_receipt.jpg', 500, 'image/jpeg');

        $this->withSession([
            'order_access_'.$transaction->invoice_number => true,
        ]);

        $response = $this->post('/checkout/proof/'.$transaction->invoice_number, [
            'payment_proof' => $file,
        ]);

        $response->assertRedirect('/checkout/success/'.$transaction->invoice_number);

        $transaction->refresh();
        $this->assertNotNull($transaction->payment_proof);
        // Important: Status remains pending until verified by admin
        $this->assertEquals('pending', $transaction->status);
        Storage::disk('public')->assertExists($transaction->payment_proof);
    }

    public function test_admin_orders_list_displays_new_transaction(): void
    {
        $transaction = Transaction::create([
            'invoice_number' => 'INV/20261119/0005',
            'customer_name' => 'Doni Siregar',
            'customer_phone' => '+6281345678901',
            'shipping_address' => 'Jl. Pahlawan No. 9, Medan',
            'total_amount' => 95000,
            'status' => 'pending',
            'bank_name' => 'BRI',
            'account_holder' => 'AKRATAMA SADAWIRA GRUP',
        ]);

        $response = $this->get('/admin/orders');

        $response->assertStatus(200);
        $response->assertSee('INV/20261119/0005');
        $response->assertSee('Doni Siregar');
        $response->assertSee('Rp 95.000');
    }
}
