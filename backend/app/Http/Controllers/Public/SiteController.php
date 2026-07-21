<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuItemResource;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Services\MenuService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SiteController extends Controller
{
    public function __construct(private readonly MenuService $menuService) {}

    public function menu(): AnonymousResourceCollection
    {
        $tree = $this->menuService->tree();

        return MenuItemResource::collection($tree);
    }

    public function page(string $slug): PageResource
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->with('menuItem')
            ->firstOrFail();

        return new PageResource($page);
    }
}
