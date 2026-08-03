<?php

declare(strict_types=1);

namespace Tests\Integration\Http\GroupLeader;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;
use Tests\Integration\OpenApi\OpenApiValidator;

final class DeleteGroupLeadersEndpointTest extends BaseApiTestCases {
    public function testReturns204WhenGroupExists() : void {
        $this->loadSchema('users');
        $this->loadSchema('group_levels');
        $this->loadSchema('groups');
        $this->loadSchema('group_leaders');

        $this->seed('group_levels', [
            [
                'id' => '770e8400-e29b-41d4-a716-446655440000',
                'name' => 'Nivå',
                'description' => 'Test',
                'sort_order' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $this->seed('groups', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'group_level_id' => '770e8400-e29b-41d4-a716-446655440000',
                'name' => 'Testgrupp',
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 0,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
                '/groups/550e8400-e29b-41d4-a716-446655440000/users',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = $this->app->handle($request);

        self::assertSame(
            204,
            $response->getStatusCode(),
        );

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups/{id}/users',
            'delete',
            $response,
        );
    }

    public function testReturns400WhenIdIsInvalid() : void {
        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
                '/groups/invalid/users',
            )
            ->withAttribute(
                'id',
                'invalid',
            );

        $response = $this->app->handle($request);

        self::assertSame(
            400,
            $response->getStatusCode(),
        );

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups/{id}/users',
            'delete',
            $response,
        );
    }
}
