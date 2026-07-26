<?php

declare(strict_types=1);

namespace Tests\Integration\Http\Group;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;
use Tests\Integration\OpenApi\OpenApiValidator;

final class CreateGroupEndpointTest extends BaseApiTestCases {
    public function testReturns201WhenRequestIsValid() : void {
        $this->loadSchema('groups');

        $requestBody = [
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
             'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 1,
        ];

        $validator = new OpenApiValidator();

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/groups')
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(
            json_encode($requestBody, JSON_THROW_ON_ERROR),
        );

        $request = $request->withParsedBody($requestBody);

        $validator->validateRequest($request);

        $response = $this->app->handle($request);

        self::assertSame(201, $response->getStatusCode());

        $validator->validateResponse(
            '/groups',
            'post',
            $response,
        );
    }

    public function testReturns400WhenRequestIsInvalid() : void {
        $this->loadSchema('groups');

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/groups')
            ->withParsedBody([
                    'groupLevelId' => 'Invalid',
                    'name' => 'Baddaren',
                    'description' => 'För nybörjare',
                    'venue' => 'Mariebad',
                    'active' => 1,
                    'competitive' => 1,
            ]);

        $response = $this->app->handle($request);

        self::assertSame(400, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups',
            'post',
            $response,
        );
    }

    public function testReturns422WhenValidationFails() : void {
        $this->loadSchema('groups');

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/groups')
            ->withParsedBody([
                'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'venue' => 'Any venue',
                'active' => 1,
                'competitive' => 1,
            ]);

        $response = $this->app->handle($request);

        self::assertSame(422, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups',
            'post',
            $response,
        );
    }
}
