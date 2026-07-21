<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    public function __construct(private readonly RoleService $roleService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Role::class);

        return RoleResource::collection(Role::with('privileges')->get());
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create($request->only('name', 'slug'));

        if ($request->filled('privilege_ids')) {
            $this->roleService->syncPrivileges($role, $request->privilege_ids);
        }

        return (new RoleResource($role->load('privileges')))->response()->setStatusCode(201);
    }

    public function show(Request $request, Role $role): RoleResource
    {
        $this->authorize('viewAny', Role::class);

        return new RoleResource($role->load('privileges'));
    }

    public function update(UpdateRoleRequest $request, Role $role): RoleResource
    {
        $role->update($request->only('name', 'slug'));

        if ($request->has('privilege_ids')) {
            $this->roleService->syncPrivileges($role, $request->privilege_ids ?? []);
        }

        return new RoleResource($role->load('privileges'));
    }

    public function destroy(Request $request, Role $role): JsonResponse
    {
        $this->authorize('delete', $role);

        $role->delete();

        return response()->json(['message' => 'Role deleted.']);
    }
}
