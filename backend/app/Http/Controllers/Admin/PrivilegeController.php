<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrivilegeRequest;
use App\Http\Requests\UpdatePrivilegeRequest;
use App\Http\Resources\PrivilegeResource;
use App\Models\Privilege;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PrivilegeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Privilege::class);

        return PrivilegeResource::collection(Privilege::all());
    }

    public function store(StorePrivilegeRequest $request): JsonResponse
    {
        $privilege = Privilege::create($request->validated());

        return (new PrivilegeResource($privilege))->response()->setStatusCode(201);
    }

    public function show(Request $request, Privilege $privilege): PrivilegeResource
    {
        $this->authorize('viewAny', Privilege::class);

        return new PrivilegeResource($privilege);
    }

    public function update(UpdatePrivilegeRequest $request, Privilege $privilege): PrivilegeResource
    {
        $privilege->update($request->validated());

        return new PrivilegeResource($privilege);
    }

    public function destroy(Request $request, Privilege $privilege): JsonResponse
    {
        $this->authorize('delete', $privilege);

        $privilege->delete();

        return response()->json(['message' => 'Privilege deleted.']);
    }
}
