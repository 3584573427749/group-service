<?php

declare(strict_types=1);

namespace Tests\Integration\Http\GroupLeader;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;
use Tests\Integration\OpenApi\OpenApiValidator;

final class GetGroupLeadersEndpointTest extends BaseApiTestCases {
    public function testReturns200AndUsersWhenGroupHasLeaders() : void {
        $this->loadSchema('users');
        $this->loadSchema('group_levels');
        $this->loadSchema('groups');
        $this->loadSchema('group_leaders');

        $this->seed('users', [
            [
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'first_name' => 'Anna',
                'last_name' => 'Andersson',
                'active' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $this->seed('group_levels', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440001',
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
                'group_level_id' => '550e8400-e29b-41d4-a716-446655440001',
                'name' => 'Testgrupp',
                'description' => null,
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 0,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $this->seed('group_leaders', [
            [
                'group_id' => '550e8400-e29b-41d4-a716-446655440000',
                'user_id' => '660e8400-e29b-41d4-a716-446655440000',
                'role' => 'Ledare',
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'GET',
                '/groups/550e8400-e29b-41d4-a716-446655440000/users',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = $this->app->handle($request);

        self::assertSame(
            200,
            $response->getStatusCode(),
        );

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups/{id}/users',
            'get',
            $response,
        );
    }

    public function testReturns200AndEmptyArrayWhenGroupHasNoLeaders() : void {
        $this->loadSchema('users');
        $this->loadSchema('group_levels');
        $this->loadSchema('groups');
        $this->loadSchema('group_leaders');

        $this->seed('group_levels', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440001',
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
                'group_level_id' => '550e8400-e29b-41d4-a716-446655440001',
                'name' => 'Testgrupp',
                'description' => null,
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 0,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'GET',
                '/groups/550e8400-e29b-41d4-a716-446655440000/users',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = $this->app->handle($request);

        self::assertSame(
            200,
            $response->getStatusCode(),
        );

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups/{id}/users',
            'get',
            $response,
        );
    }

    public function testReturns400WhenIdIsInvalid() : void {
        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'GET',
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
            'get',
            $response,
        );
    }

    public function testReturns404WhenGroupDoesNotExist() : void {
        $this->loadSchema('users');
        $this->loadSchema('group_levels');
        $this->loadSchema('groups');
        $this->loadSchema('group_leaders');

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'GET',
                '/groups/550e8400-e29b-41d4-a716-446655440000/users',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = $this->app->handle($request);

        self::assertSame(
            404,
            $response->getStatusCode(),
        );

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups/{id}/users',
            'get',
            $response,
        );
    }
}
