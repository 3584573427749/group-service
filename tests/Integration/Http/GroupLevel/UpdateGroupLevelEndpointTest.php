<?php

declare(strict_types=1);

namespace Tests\Integration\Http\GroupLevel;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;
use Tests\Integration\OpenApi\OpenApiValidator;

final class UpdateGroupLevelEndpointTest extends BaseApiTestCases {
    public function testReturns200WhenRequestIsValid() : void {
        $this->loadSchema('group_levels');

        $this->seed('group_levels', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'sort_order' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $requestBody = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Pingvinen',
            'description' => 'Kan simma själv',
            'sortOrder' => 2,
        ];

        $validator = new OpenApiValidator();

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/550e8400-e29b-41d4-a716-446655440000',
            )
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(
            json_encode($requestBody, JSON_THROW_ON_ERROR),
        );

        $request = $request
            ->withParsedBody($requestBody)
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $validator->validateRequest($request);

        $response = $this->app->handle($request);

        self::assertSame(200, $response->getStatusCode());

        $validator->validateResponse(
            '/group-levels/{id}',
            'put',
            $response,
        );

        $payload = json_decode(
            (string) $response->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        self::assertSame(
            'Pingvinen',
            $payload['data']['name'],
        );

        self::assertSame(
            'Kan simma själv',
            $payload['data']['description'],
        );

        self::assertSame(
            2,
            $payload['data']['sortOrder'],
        );
    }

    public function testReturns404WhenGroupLevelDoesNotExist() : void {
        $this->loadSchema('group_levels');

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/550e8400-e29b-41d4-a716-446655440000',
            )
            ->withParsedBody([
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'Pingvinen',
                'description' => 'Kan simma själv',
                'sortOrder' => 2,
            ])
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = $this->app->handle($request);

        self::assertSame(404, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/group-levels/{id}',
            'put',
            $response,
        );
    }

    public function testReturns422WhenValidationFails() : void {
        $this->loadSchema('group_levels');

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/550e8400-e29b-41d4-a716-446655440000',
            )
            ->withParsedBody([
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'name' => 'A',
                'sortOrder' => 'abc',
            ])
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = $this->app->handle($request);

        self::assertSame(422, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/group-levels/{id}',
            'put',
            $response,
        );
    }

    public function testReturns400WhenIdIsInvalid() : void {
        $this->loadSchema('group_levels');

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/invalid',
            )
            ->withParsedBody([
                'id' => 'invalid',
                'name' => 'Pingvinen',
                'description' => 'Kan simma själv',
                'sortOrder' => 2,
            ])
            ->withAttribute(
                'id',
                'invalid',
            );

        $response = $this->app->handle($request);

        self::assertSame(400, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/group-levels/{id}',
            'put',
            $response,
        );
    }
}
