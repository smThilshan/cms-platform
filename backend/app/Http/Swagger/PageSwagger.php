<?php

namespace App\Http\Swagger;

use OpenApi\Attributes as OA;

class PageSwagger
{
    #[OA\Get(
        path: '/api/admin/pages',
        summary: 'List all pages (paginated) — requires privilege pages.list',
        security: [['bearerAuth' => []]],
        tags: ['Pages'],
        responses: [
            new OA\Response(response: 200, description: 'Paginated pages', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/PageResource')),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — missing privilege pages.list'),
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: '/api/admin/pages',
        summary: 'Create a page — requires privilege pages.create',
        security: [['bearerAuth' => []]],
        tags: ['Pages'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['title', 'body', 'menu_item_id', 'status'],
                    properties: [
                        new OA\Property(property: 'title',        type: 'string',  example: 'About Us'),
                        new OA\Property(property: 'body',         type: 'string',  example: '<p>Content</p>'),
                        new OA\Property(property: 'menu_item_id', type: 'integer', example: 1),
                        new OA\Property(property: 'status',       type: 'string',  enum: ['draft', 'published']),
                        new OA\Property(property: 'cover_image',  type: 'string',  format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Page created', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/PageResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — missing privilege pages.create'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function store() {}

    #[OA\Get(
        path: '/api/admin/pages/{id}',
        summary: 'Get a single page — requires privilege pages.list',
        security: [['bearerAuth' => []]],
        tags: ['Pages'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        responses: [
            new OA\Response(response: 200, description: 'Page detail', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/PageResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: '/api/admin/pages/{id}',
        summary: 'Update a page — requires privilege pages.edit. Use POST for multipart.',
        security: [['bearerAuth' => []]],
        tags: ['Pages'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(properties: [
                    new OA\Property(property: 'title',        type: 'string'),
                    new OA\Property(property: 'body',         type: 'string'),
                    new OA\Property(property: 'menu_item_id', type: 'integer'),
                    new OA\Property(property: 'status',       type: 'string', enum: ['draft', 'published']),
                    new OA\Property(property: 'cover_image',  type: 'string', format: 'binary'),
                ])
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Page updated', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/PageResource'),
            ])),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — missing privilege pages.edit'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: '/api/admin/pages/{id}',
        summary: 'Delete a page — Admin only (requires privilege pages.delete). Moderators get 403.',
        security: [['bearerAuth' => []]],
        tags: ['Pages'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        responses: [
            new OA\Response(response: 200, description: 'Deleted', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden — Moderators cannot delete pages'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy() {}

    #[OA\Get(
        path: '/api/pages/{slug}',
        summary: 'Get a published page by slug — no auth required',
        tags: ['Public'],
        parameters: [new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'about-us')],
        responses: [
            new OA\Response(response: 200, description: 'Published page', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'data', ref: '#/components/schemas/PageResource'),
            ])),
            new OA\Response(response: 404, description: 'Page not found or not published'),
        ]
    )]
    public function publicPage() {}
}
