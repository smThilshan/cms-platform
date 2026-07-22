<?php

namespace App\Http\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'CMS Platform API',
    version: '1.0.0',
    description: 'Content Management System — Laravel 12 + Sanctum. All admin endpoints require a Bearer token from POST /api/login.',
    contact: new OA\Contact(email: 'admin@cms.test')
)]
#[OA\Server(url: L5_SWAGGER_CONST_HOST, description: 'Local development server')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Paste the token from POST /api/login'
)]
#[OA\Tag(name: 'Auth',        description: 'Authentication — login, logout, current user')]
#[OA\Tag(name: 'Pages',       description: 'Admin — page management (CRUD)')]
#[OA\Tag(name: 'Menu Items',  description: 'Admin — menu management (CRUD + reorder)')]
#[OA\Tag(name: 'Roles',       description: 'Admin — role management (CRUD)')]
#[OA\Tag(name: 'Privileges',  description: 'Admin — privilege management (CRUD)')]
#[OA\Tag(name: 'Public',      description: 'Public — no authentication required')]

// ── Reusable schemas ────────────────────────────────────────────────────────
#[OA\Schema(
    schema: 'PrivilegeResource',
    properties: [
        new OA\Property(property: 'id',          type: 'integer', example: 1),
        new OA\Property(property: 'key',         type: 'string',  example: 'pages.delete'),
        new OA\Property(property: 'description', type: 'string',  example: 'Delete pages'),
    ]
)]
#[OA\Schema(
    schema: 'RoleResource',
    properties: [
        new OA\Property(property: 'id',         type: 'integer', example: 1),
        new OA\Property(property: 'name',       type: 'string',  example: 'Admin'),
        new OA\Property(property: 'slug',       type: 'string',  example: 'admin'),
        new OA\Property(property: 'privileges', type: 'array', items: new OA\Items(ref: '#/components/schemas/PrivilegeResource')),
    ]
)]
#[OA\Schema(
    schema: 'UserResource',
    properties: [
        new OA\Property(property: 'id',         type: 'integer', example: 1),
        new OA\Property(property: 'name',       type: 'string',  example: 'Admin User'),
        new OA\Property(property: 'email',      type: 'string',  example: 'admin@cms.test'),
        new OA\Property(property: 'role',       ref: '#/components/schemas/RoleResource'),
        new OA\Property(property: 'privileges', type: 'array', items: new OA\Items(type: 'string', example: 'pages.delete')),
    ]
)]
#[OA\Schema(
    schema: 'MenuItemResource',
    properties: [
        new OA\Property(property: 'id',        type: 'integer', example: 1),
        new OA\Property(property: 'title',     type: 'string',  example: 'Company'),
        new OA\Property(property: 'slug',      type: 'string',  example: 'company'),
        new OA\Property(property: 'order',     type: 'integer', example: 1),
        new OA\Property(property: 'parent_id', type: 'integer', nullable: true, example: null),
        new OA\Property(property: 'children',  type: 'array', items: new OA\Items(ref: '#/components/schemas/MenuItemResource')),
        new OA\Property(property: 'pages',     type: 'array', items: new OA\Items(ref: '#/components/schemas/PageResourceBrief')),
    ]
)]
#[OA\Schema(
    schema: 'PageResourceBrief',
    properties: [
        new OA\Property(property: 'id',          type: 'integer', example: 1),
        new OA\Property(property: 'title',       type: 'string',  example: 'About Us'),
        new OA\Property(property: 'slug',        type: 'string',  example: 'about-us'),
        new OA\Property(property: 'cover_image', type: 'string',  nullable: true),
        new OA\Property(property: 'status',      type: 'string',  enum: ['draft', 'published']),
    ]
)]
#[OA\Schema(
    schema: 'PageResource',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/PageResourceBrief'),
        new OA\Schema(properties: [
            new OA\Property(property: 'body',       type: 'string', example: '<p>Content</p>'),
            new OA\Property(property: 'menu_item',  ref: '#/components/schemas/MenuItemResource'),
            new OA\Property(property: 'created_at', type: 'string', example: '2026-07-22 10:00:00'),
            new OA\Property(property: 'updated_at', type: 'string', example: '2026-07-22 10:00:00'),
        ]),
    ]
)]
#[OA\Schema(
    schema: 'ValidationError',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'The title field is required.'),
        new OA\Property(property: 'errors',  type: 'object'),
    ]
)]
#[OA\Schema(
    schema: 'MessageResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Operation successful.'),
    ]
)]
class SwaggerInfo {}
