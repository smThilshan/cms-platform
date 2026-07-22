<?php

namespace App\Http\Swagger;

use OpenApi\Attributes as OA;

class AuthSwagger
{
    #[OA\Post(
        path: '/api/login',
        summary: 'Login and receive a Sanctum token',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email',    type: 'string', format: 'email', example: 'admin@cms.test'),
                    new OA\Property(property: 'password', type: 'string', example: 'password'),
                ]
            )
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'token', type: 'string', example: '1|abc123'),
                    new OA\Property(property: 'user',  ref: '#/components/schemas/UserResource'),
                ])
            ),
            new OA\Response(response: 401, description: 'Invalid credentials'),
            new OA\Response(response: 422, description: 'Validation error', content: new OA\JsonContent(ref: '#/components/schemas/ValidationError')),
        ]
    )]
    public function login() {}

    #[OA\Post(
        path: '/api/logout',
        summary: 'Revoke the current token',
        security: [['bearerAuth' => []]],
        tags: ['Auth'],
        responses: [
            new OA\Response(response: 200, description: 'Logged out', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function logout() {}

    #[OA\Get(
        path: '/api/me',
        summary: 'Get the authenticated user with role and privileges array',
        security: [['bearerAuth' => []]],
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Current user',
                content: new OA\JsonContent(properties: [
                    new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
                ])
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function me() {}
}
