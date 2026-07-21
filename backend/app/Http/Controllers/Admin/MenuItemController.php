<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReorderMenuItemsRequest;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use App\Http\Resources\MenuItemResource;
use App\Models\MenuItem;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MenuItemController extends Controller
{
    public function __construct(private readonly MenuService $menuService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', MenuItem::class);

        $items = $this->menuService->tree();

        return MenuItemResource::collection($items);
    }

    public function store(StoreMenuItemRequest $request): JsonResponse
    {
        $menuItem = $this->menuService->create($request->validated());

        return (new MenuItemResource($menuItem))->response()->setStatusCode(201);
    }

    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem): MenuItemResource
    {
        $menuItem = $this->menuService->update($menuItem, $request->validated());

        return new MenuItemResource($menuItem->load('children.pages', 'pages'));
    }

    public function destroy(Request $request, MenuItem $menuItem): JsonResponse
    {
        $this->authorize('delete', $menuItem);

        $menuItem->delete();

        return response()->json(['message' => 'Menu item deleted.']);
    }

    public function reorder(ReorderMenuItemsRequest $request): JsonResponse
    {
        $this->menuService->reorder($request->validated('items'));

        return response()->json(['message' => 'Menu reordered.']);
    }
}
