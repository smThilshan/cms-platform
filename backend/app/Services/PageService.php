<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageService
{
    public function create(array $data, ?UploadedFile $image): Page
    {
        $data['slug']        = $this->uniqueSlug($data['title']);
        $data['cover_image'] = $image ? $this->storeImage($image) : null;

        return Page::create($data);
    }

    public function update(Page $page, array $data, ?UploadedFile $image): Page
    {
        if (isset($data['title']) && $data['title'] !== $page->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $page->id);
        }

        if ($image) {
            $this->deleteImage($page->cover_image);
            $data['cover_image'] = $this->storeImage($image);
        }

        $page->update($data);

        return $page->fresh();
    }

    public function delete(Page $page): void
    {
        $this->deleteImage($page->cover_image);
        $page->delete();
    }

    private function storeImage(UploadedFile $image): string
    {
        return $image->store('covers', 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug  = Str::slug($title);
        $base  = $slug;
        $count = 1;

        while (
            Page::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }
}
