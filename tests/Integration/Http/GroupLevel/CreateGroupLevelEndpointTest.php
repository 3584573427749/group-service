<?php

declare(strict_types=1);

namespace Tests\Integration\Http\GroupLevel;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;
use Tests\Integration\OpenApi\OpenApiValidator;

final class CreateGroupLevelEndpointTest extends BaseApiTestCases {
    public function testReturns201WhenRequestIsValid() : void {
        $this->loadSchema('group_levels');

        $requestBody = [
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'sortOrder' => 1,
        ];

        $validator = new OpenApiValidator();

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/group-levels')
            ->withHeader('Content-Type', 'application/json');

        $request->getBody()->write(
            json_encode($requestBody, JSON_THROW_ON_ERROR),
        );

        $request = $request->withParsedBody($requestBody);

        $validator->validateRequest($request);

        $response = $this->app->handle($request);

        self::assertSame(201, $response->getStatusCode());

        $validator->validateResponse(
            '/group-levels',
            'post',
            $response,
        );
    }

    public function testReturns422WhenValidationFails() : void {
        $this->loadSchema('group_levels');

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/group-levels')
            ->withParsedBody([
                'name' => 'A',
                'sortOrder' => 'abc',
            ]);

        $response = $this->app->handle($request);

        self::assertSame(422, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/group-levels',
            'post',
            $response,
        );
    }
}
