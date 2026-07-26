<?php

declare(strict_types=1);

namespace Tests\Integration\Http\Group;

use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Integration\BaseApiTestCases;
use Tests\Integration\OpenApi\OpenApiValidator;

final class GetAllGroupsEndpointTest extends BaseApiTestCases {
    public function testReturns200AndEmptyArrayWhenNoGroupLevelsExist() : void {
        $this->loadSchema('groups');

        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/groups');

        $response = $this->app->handle($request);

        self::assertSame(200, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups',
            'get',
            $response,
        );

        $payload = json_decode(
            (string)$response->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        self::assertSame([], $payload['data']);
    }

    public function testReturns200AndGroups() : void {
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
            [
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'group_level_id' => '650e8400-e29b-41d4-a716-446655440000',
                'name' => 'Pingvinen',
                'description' => 'Kan simma själv',
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/groups');

        $response = $this->app->handle($request);

        self::assertSame(200, $response->getStatusCode());

        $validator = new OpenApiValidator();

        $validator->validateResponse(
            '/groups',
            'get',
            $response,
        );

        $payload = json_decode(
            (string)$response->getBody(),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        self::assertCount(2, $payload['data']);

        $ids = array_column($payload['data'], 'id');

        self::assertContains('550e8400-e29b-41d4-a716-446655440000', $ids);
        self::assertContains('660e8400-e29b-41d4-a716-446655440000', $ids);
    }
}
