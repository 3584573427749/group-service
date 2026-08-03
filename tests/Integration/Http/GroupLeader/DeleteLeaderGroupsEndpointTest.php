<?php

declare(strict_types=1);

namespace Tests\Integration\Http\GroupLeader;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;
use Tests\Integration\OpenApi\OpenApiValidator;

final class DeleteLeaderGroupsEndpointTest extends BaseApiTestCases {
    public function testReturns204WhenUserIdIsValid() : void {
        $this->loadSchema('users');
        $this->loadSchema('group_levels');
        $this->loadSchema('groups');
        $this->loadSchema('group_leaders');

        $this->seed('users', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'first_name' => 'Anna',
                'last_name' => 'Andersson',
                'active' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
                '/users/550e8400-e29b-41d4-a716-446655440000/groups',
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
            '/users/{id}/groups',
            'delete',
            $response,
        );
    }

    public function testReturns400WhenUserIdIsInvalid() : void {
        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
                '/users/invalid/groups',
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
            '/users/{id}/groups',
            'delete',
            $response,
        );
    }
}
