<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Services\PageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PageController extends Controller
{
    public function __construct(private readonly PageService $pageService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Page::class);

        $pages = Page::with('menuItem')->latest()->paginate(15);

        return PageResource::collection($pages);
    }

    public function store(StorePageRequest $request): PageResource
    {
        $page = $this->pageService->create(
            $request->validated(),
            $request->file('cover_image')
        );

        return new PageResource($page->load('menuItem'));
    }

    public function show(Request $request, Page $page): PageResource
    {
        $this->authorize('viewAny', Page::class);

        return new PageResource($page->load('menuItem'));
    }

    public function update(UpdatePageRequest $request, Page $page): PageResource
    {
        $page = $this->pageService->update(
            $page,
            $request->validated(),
            $request->file('cover_image')
        );

        return new PageResource($page->load('menuItem'));
    }

    public function destroy(Request $request, Page $page): JsonResponse
    {
        $this->authorize('delete', $page);

        $this->pageService->delete($page);

        return response()->json(['message' => 'Page deleted.']);
    }
}
