<?php

declare(strict_types=1);

namespace Tests\Integration\Http\GroupLevel;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;
use Tests\Integration\OpenApi\OpenApiValidator;

final class UpdateGroupLevelSortOrderEndpointTest extends BaseApiTestCases {
    public function testReturns204WhenRequestIsValid() : void {
        $this->loadSchema('group_levels');

        $this->seed('group_levels', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'Nivå 1',
                'sort_order' => 10,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
            [
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'name' => 'Pingvinen',
                'description' => 'Nivå 2',
                'sort_order' => 20,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $requestBody = [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => 1,
            ],
            [
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => 2,
            ],
        ];

        $validator = new OpenApiValidator();

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/sortorder',
            )
            ->withHeader(
                'Content-Type',
                'application/json',
            );

        $request->getBody()->write(
            json_encode(
                $requestBody,
                JSON_THROW_ON_ERROR,
            ),
        );

        $request = $request->withParsedBody(
            $requestBody,
        );

        $validator->validateRequest($request);

        $response = $this->app->handle($request);

        self::assertSame(
            204,
            $response->getStatusCode(),
        );

        $validator->validateResponse(
            '/group-levels/sortorder',
            'put',
            $response,
        );
    }

    public function testReturns422WhenValidationFails() : void {
        $requestBody = [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
            ],
        ];

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/sortorder',
            )
            ->withParsedBody(
                $requestBody,
            );

        $response = $this->app->handle($request);

        self::assertSame(
            422,
            $response->getStatusCode(),
        );

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/group-levels/sortorder',
            'put',
            $response,
        );
    }

    public function testReturns400WhenIdIsInvalid() : void {
        $requestBody = [
            [
                'id' => 'invalid-id',
                'sortOrder' => 1,
            ],
        ];

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/sortorder',
            )
            ->withParsedBody(
                $requestBody,
            );

        $response = $this->app->handle($request);

        self::assertSame(
            400,
            $response->getStatusCode(),
        );

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/group-levels/sortorder',
            'put',
            $response,
        );
    }
}
