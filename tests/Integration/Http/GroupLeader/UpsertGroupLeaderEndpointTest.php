<?php

declare(strict_types=1);

namespace Tests\Integration\Http\GroupLeader;

use App\Domain\Enums\Role;
use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;
use Tests\Integration\OpenApi\OpenApiValidator;

final class UpsertGroupLeaderEndpointTest extends BaseApiTestCases {
    public function testReturns204WhenRequestIsValid() : void {
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
                'description' => null,
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 0,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $requestBody = [
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => Role::LEADER->value,
        ];

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'POST',
                '/groups/550e8400-e29b-41d4-a716-446655440000/users',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            )
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(
            json_encode($requestBody, JSON_THROW_ON_ERROR),
        );

        $response = $this->app->handle($request);

        self::assertSame(
            204,
            $response->getStatusCode(),
        );

        $validator = new OpenApiValidator();

        $validator->validateRequest($request);

        $validator->validateResponse(
            '/groups/{id}/users',
            'post',
            $response,
        );
    }

    public function testReturns422WhenValidationFails() : void {
        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'POST',
                '/groups/550e8400-e29b-41d4-a716-446655440000/users',
            )
            ->withParsedBody([
                'groupId' => '',
                'userId' => '',
                'role' => '',
            ])
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = $this->app->handle($request);

        self::assertSame(
            422,
            $response->getStatusCode(),
        );

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups/{id}/users',
            'post',
            $response,
        );
    }

    public function testReturns400WhenGroupIdIsInvalid() : void {
        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'POST',
                '/groups/invalid/users',
            )
            ->withParsedBody([
                'groupId' => 'invalid',
                'userId' => '660e8400-e29b-41d4-a716-446655440000',
                'role' => Role::LEADER->value,
            ])
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
            'post',
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
                'POST',
                '/groups/550e8400-e29b-41d4-a716-446655440000/users',
            )
            ->withParsedBody([
                'groupId' => '550e8400-e29b-41d4-a716-446655440000',
                'userId' => '660e8400-e29b-41d4-a716-446655440000',
                'role' => Role::LEADER->value,
            ])
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
            'post',
            $response,
        );
    }
}
