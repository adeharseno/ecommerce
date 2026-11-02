<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductCmsController extends Controller
{
    public function __construct(
        private readonly ProductImageService $imageService,
    ) {}

    public function index(): View
    {
        $products = Product::latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['images']);
        $data['slug'] = $this->uniqueSlug($request->name);

        // Upload images and store their paths
        $uploadedPaths = [];
        if ($request->hasFile('images')) {
            $uploadedPaths = $this->imageService->storeMany($request->file('images'));
        }
        $data['images'] = $uploadedPaths;

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->safe()->except(['images', 'removed_images']);

        // Update slug only when name changes
        if ($request->name !== $product->name) {
            $data['slug'] = $this->uniqueSlug($request->name, $product->id);
        }

        // 1. Remove images the user marked for deletion
        $currentImages = $product->images ?? [];
        $removedPaths = $request->input('removed_images', []);

        if (! empty($removedPaths)) {
            $this->imageService->deleteMany($removedPaths);
            $currentImages = array_values(array_diff($currentImages, $removedPaths));
        }

        // 2. Upload new images and append to remaining ones
        if ($request->hasFile('images')) {
            $newPaths = $this->imageService->storeMany($request->file('images'));
            $currentImages = array_merge($currentImages, $newPaths);
        }

        $data['images'] = $currentImages;

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        // Delete all associated image files from storage
        $this->imageService->deleteMany($product->images ?? []);

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Generate a slug from the product name that is unique in the products table,
     * optionally ignoring a given product ID (used during update).
     */
    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $count = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
