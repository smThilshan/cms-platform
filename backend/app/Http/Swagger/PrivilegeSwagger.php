<?php

namespace App\Http\Swagger;

use OpenApi\Attributes as OA;

class PrivilegeSwagger
{
    #[OA\Get(
        path: '/api/admin/privileges',
        summary: 'List all privileges — Admin only (requires privilege privileges.list)',
        security: [['bearerAuth' => []]],
        tags: ['Privileges'],
        responses: [
            new OA\Response(response: 200, description: 'Privileges list', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/PrivilegeResource')),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — Moderators cannot access privileges'),
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: '/api/admin/privileges',
        summary: 'Create a privilege — Admin only',
        security: [['bearerAuth' => []]],
        tags: ['Privileges'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['key', 'description'],
                properties: [
                    new OA\Property(property: 'key',         type: 'string', example: 'reports.view'),
                    new OA\Property(property: 'description', type: 'string', example: 'View reports'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/PrivilegeResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(
        path: '/api/admin/privileges/{id}',
        summary: 'Get a single privilege — Admin only',
        security: [['bearerAuth' => []]],
        tags: ['Privileges'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        responses: [
            new OA\Response(response: 200, description: 'Privilege detail', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/PrivilegeResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: '/api/admin/privileges/{id}',
        summary: 'Update a privilege — Admin only',
        security: [['bearerAuth' => []]],
        tags: ['Privileges'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'key',         type: 'string', example: 'reports.export'),
                new OA\Property(property: 'description', type: 'string', example: 'Export reports'),
            ])
        ),
        responses: [
            new OA\Response(response: 200, description: 'Updated', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/PrivilegeResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/admin/privileges/{id}',
        summary: 'Delete a privilege — Admin only',
        security: [['bearerAuth' => []]],
        tags: ['Privileges'],
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
