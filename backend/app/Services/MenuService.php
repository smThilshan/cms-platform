<?php

namespace App\Services;

use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuService
{
    public function tree(): \Illuminate\Database\Eloquent\Collection
    {
        return MenuItem::with(['children.pages', 'pages'])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
    }

    public function create(array $data): MenuItem
    {
        $data['slug']  = $this->uniqueSlug($data['title']);
        $data['order'] = $data['order'] ?? $this->nextOrder($data['parent_id'] ?? null);

        return MenuItem::create($data);
    }

    public function update(MenuItem $menuItem, array $data): MenuItem
    {
        if (isset($data['title']) && $data['title'] !== $menuItem->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $menuItem->id);
        }

        $menuItem->update($data);

        return $menuItem->fresh();
    }

    public function reorder(array $items): void
    {
        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                MenuItem::where('id', $item['id'])->update([
                    'order'     => $item['order'],
                    'parent_id' => $item['parent_id'] ?? null,
                ]);
            }
        });
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug  = Str::slug($title);
        $base  = $slug;
        $count = 1;

        while (
            MenuItem::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$count}";
            $count++;
        }

        return $slug;
    }

    private function nextOrder(?int $parentId): int
    {
        return MenuItem::where('parent_id', $parentId)->max('order') + 1;
    }
}
