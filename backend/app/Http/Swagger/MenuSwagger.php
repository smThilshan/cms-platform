<?php

namespace App\Http\Swagger;

use OpenApi\Attributes as OA;

class MenuSwagger
{
    #[OA\Get(
        path: '/api/menu',
        summary: 'Public menu tree with published pages — no auth required',
        tags: ['Public'],
        responses: [
            new OA\Response(response: 200, description: 'Menu tree', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/MenuItemResource')),
            ])),
        ]
    )]
    public function publicMenu() {}

    #[OA\Get(
        path: '/api/admin/menu-items',
        summary: 'List menu items as tree (all statuses) — requires privilege menu.list',
        security: [['bearerAuth' => []]],
        tags: ['Menu Items'],
        responses: [
            new OA\Response(response: 200, description: 'Menu tree', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/MenuItemResource')),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — missing privilege menu.list'),
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: '/api/admin/menu-items',
        summary: 'Create a menu item — requires privilege menu.create',
        security: [['bearerAuth' => []]],
        tags: ['Menu Items'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title'],
                properties: [
                    new OA\Property(property: 'title',     type: 'string',  example: 'Company'),
                    new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: null),
                    new OA\Property(property: 'order',     type: 'integer', example: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/MenuItemResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Post(
        path: '/api/admin/menu-items/reorder',
        summary: 'Bulk reorder menu items (drag-and-drop sync) — requires privilege menu.reorder',
        security: [['bearerAuth' => []]],
        tags: ['Menu Items'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['items'],
                properties: [
                    new OA\Property(
                        property: 'items',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id',        type: 'integer', example: 1),
                                new OA\Property(property: 'order',     type: 'integer', example: 2),
                                new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: null),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Reordered', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — missing privilege menu.reorder'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function reorder() {}

    #[OA\Post(
        path: '/api/admin/menu-items/{id}',
        summary: 'Update a menu item — requires privilege menu.edit',
        security: [['bearerAuth' => []]],
        tags: ['Menu Items'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'title',     type: 'string',  example: 'About'),
                new OA\Property(property: 'parent_id', type: 'integer', nullable: true),
                new OA\Property(property: 'order',     type: 'integer', example: 2),
            ])
        ),
        responses: [
            new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/MenuItemResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/admin/menu-items/{id}',
        summary: 'Delete menu item — Admin only. Cascades to child items and pages.',
        security: [['bearerAuth' => []]],
        tags: ['Menu Items'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        responses: [
            new OA\Response(response: 200, description: 'Deleted', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — Moderators cannot delete'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy() {}
}
