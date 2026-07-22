<?php

namespace App\Http\Swagger;

use OpenApi\Attributes as OA;

class RoleSwagger
{
    #[OA\Get(
        path: '/api/admin/roles',
        summary: 'List all roles with privileges — Admin only (requires privilege roles.list)',
        security: [['bearerAuth' => []]],
        tags: ['Roles'],
        responses: [
            new OA\Response(response: 200, description: 'Roles list', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/RoleResource')),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — Moderators cannot access roles'),
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: '/api/admin/roles',
        summary: 'Create a role and assign privileges — Admin only',
        security: [['bearerAuth' => []]],
        tags: ['Roles'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: 'name',          type: 'string',  example: 'Editor'),
                    new OA\Property(property: 'slug',          type: 'string',  example: 'editor'),
                    new OA\Property(property: 'privilege_ids', type: 'array', nullable: true, items: new OA\Items(type: 'integer', example: 1)),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Role created', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/RoleResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(
        path: '/api/admin/roles/{id}',
        summary: 'Get a role — Admin only',
        security: [['bearerAuth' => []]],
        tags: ['Roles'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        responses: [
            new OA\Response(response: 200, description: 'Role detail', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/RoleResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: '/api/admin/roles/{id}',
        summary: 'Update a role and sync privileges — Admin only',
        security: [['bearerAuth' => []]],
        tags: ['Roles'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'name',          type: 'string', example: 'Senior Editor'),
                new OA\Property(property: 'slug',          type: 'string', example: 'senior-editor'),
                new OA\Property(property: 'privilege_ids', type: 'array', nullable: true, items: new OA\Items(type: 'integer')),
            ])
        ),
        responses: [
            new OA\Response(response: 200, description: 'Role updated', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/RoleResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/admin/roles/{id}',
        summary: 'Delete a role — Admin only',
        security: [['bearerAuth' => []]],
        tags: ['Roles'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        responses: [
            new OA\Response(response: 200, description: 'Deleted', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy() {}
}
