<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Handles product image uploads and deletions in the public disk.
 *
 * All images are stored under storage/app/public/products/
 * and accessible via /storage/products/{filename}.
 */
class ProductImageService
{
    private const DISK = 'public';

    private const DIRECTORY = 'products';

    /**
     * Store multiple uploaded images and return their storage paths.
     *
     * @param  list<UploadedFile>  $files
     * @return list<string>
     */
    public function storeMany(array $files): array
    {
        return array_map(
            fn (UploadedFile $file): string => $file->store(self::DIRECTORY, self::DISK),
            $files,
        );
    }

    /**
     * Delete one image file from storage.
     */
    public function delete(string $path): void
    {
        if (Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    /**
     * Delete a list of image paths from storage.
     *
     * @param  list<string>  $paths
     */
    public function deleteMany(array $paths): void
    {
        foreach ($paths as $path) {
            $this->delete($path);
        }
    }
}
