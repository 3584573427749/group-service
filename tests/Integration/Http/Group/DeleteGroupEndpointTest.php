<?php

declare(strict_types=1);

namespace Tests\Integration\Http\Group;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;
use Tests\Integration\OpenApi\OpenApiValidator;

final class DeleteGroupEndpointTest extends BaseApiTestCases {
    public function testReturns204WhenGroupLevelExists() : void {
        $this->loadSchema('groups');

        $this->seed('groups', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'group_level_id' => '650e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
                '/groups/550e8400-e29b-41d4-a716-446655440000',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = $this->app->handle($request);

        self::assertSame(204, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups/{id}',
            'delete',
            $response,
        );
    }

    public function testReturns404WhenGroupLevelDoesNotExist() : void {
        $this->loadSchema('groups');

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
                '/groups/550e8400-e29b-41d4-a716-446655440000',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = $this->app->handle($request);

        self::assertSame(404, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups/{id}',
            'delete',
            $response,
        );
    }

    public function testReturns400WhenIdIsInvalid() : void {
        $this->loadSchema('groups');

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
                '/groups/invalid',
            )
            ->withAttribute(
                'id',
                'invalid',
            );

        $response = $this->app->handle($request);

        self::assertSame(400, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups/{id}',
            'delete',
            $response,
        );
    }
}
