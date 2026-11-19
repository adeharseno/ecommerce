<?php

namespace Tests\Feature;

use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaticPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PageSeeder::class);
    }

    public function test_about_page_renders_successfully(): void
    {
        $response = $this->get('/page/about');

        $response->assertStatus(200);
        $response->assertSee('Tentang Kami');
        $response->assertSee('Sumatra Briks');
        $response->assertSee('Akratama Sadawira Grup', false);
        $response->assertSee('Visi & Misi', false);
    }

    public function test_qna_page_renders_successfully(): void
    {
        $response = $this->get('/page/qna');

        $response->assertStatus(200);
        $response->assertSee('Pertanyaan Umum');
        $response->assertSee('20601010804563');
        $response->assertSee('AKRATAMA SADAWIRA GRUP');
        $response->assertSee('Cube C25');
    }

    public function test_privacy_policy_page_renders_successfully(): void
    {
        $response = $this->get('/page/kebijakan-privasi');

        $response->assertStatus(200);
        $response->assertSee('Kebijakan Privasi');
        $response->assertSee('AKRATAMA SADAWIRA GRUP');
        $response->assertSee('Penggunaan Informasi');
    }

    public function test_terms_and_conditions_page_renders_successfully(): void
    {
        $response = $this->get('/page/term-and-condition');

        $response->assertStatus(200);
        $response->assertSee('Syarat', false);
        $response->assertSee('Ketentuan', false);
        $response->assertSee('AKRATAMA SADAWIRA GRUP');
        $response->assertSee('20601010804563');
    }

    public function test_terms_page_aliases_work_properly(): void
    {
        $this->get('/page/terms-and-conditions')->assertStatus(200);
        $this->get('/page/terms')->assertStatus(200);
        $this->get('/page/syarat-ketentuan')->assertStatus(200);
    }

    public function test_return_policy_page_renders_successfully(): void
    {
        $response = $this->get('/page/return-policy');

        $response->assertStatus(200);
        $response->assertSee('Kebijakan Pengembalian', false);
        $response->assertSee('AKRATAMA SADAWIRA GRUP');
        $response->assertSee('Video Unboxing', false);
        $response->assertSee('Replacement', false);
        $response->assertSee('Refund', false);
    }

    public function test_return_policy_aliases_work_properly(): void
    {
        $this->get('/page/kebijakan-pengembalian')->assertStatus(200);
        $this->get('/page/refund-policy')->assertStatus(200);
        $this->get('/page/returns')->assertStatus(200);
        $this->get('/page/pengembalian')->assertStatus(200);
    }

    public function test_footer_contains_links_to_all_static_pages(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(route('page.show', 'about'));
        $response->assertSee(route('page.show', 'qna'));
        $response->assertSee(route('page.show', 'kebijakan-privasi'));
        $response->assertSee(route('page.show', 'term-and-condition'));
        $response->assertSee(route('page.show', 'return-policy'));
    }
}
