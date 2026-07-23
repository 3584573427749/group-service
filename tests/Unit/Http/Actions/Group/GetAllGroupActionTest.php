<?php

declare(strict_types=1);

namespace Http\Actions\Group;

use App\Application\Handlers\Group\GetGroupHandler;
use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\Entities\Group;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;
use App\Http\Actions\Group\GetAllGroupsAction;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class GetAllGroupActionTest extends TestCase {
    public function testReturnsGroups() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $group = new Group(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
            new GroupLevelId('660e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            'Arena',
            1,
            1,
            new DateTimeValue('2026-06-10T10:00:00+00:00'),
            null,
        );

        $dto = GroupDTO::fromEntity($group);

        $handler = $this->createMock(GetGroupHandler::class);

        $handler
            ->expects(self::once())
            ->method('getAll')
            ->willReturn([$dto]);

        $action = new GetAllGroupsAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/group');

        $response = (new ResponseFactory())->createResponse();

        $result = $action($request, $response, []);

        self::assertSame(200, $result->getStatusCode());

        $payload = $this->decodeJsonResponse($result);

        self::assertSame(200, $payload['statusCode']);

        self::assertArrayHasKey('data', $payload);

        self::assertCount(1, $payload['data']);

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $payload['data'][0]['id'],
        );

        self::assertSame(
            'Baddaren',
            $payload['data'][0]['name'],
        );

        self::assertSame(
            'För nybörjare',
            $payload['data'][0]['description'],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeJsonResponse(
        ResponseInterface $response,
    ) : array {
        $body = (string)$response->getBody();

        self::assertNotSame('', $body);

        $decoded = json_decode($body, true);

        self::assertIsArray($decoded);

        return $decoded;
    }

    public function testReturnsEmptyArrayWhenNoGroupsExist() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(GetGroupHandler::class);

        $handler
            ->expects(self::once())
            ->method('getAll')
            ->willReturn([]);

        $action = new GetAllGroupsAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest('GET', '/group');

        $response = (new ResponseFactory())->createResponse();

        $result = $action($request, $response, []);

        self::assertSame(200, $result->getStatusCode());

        $payload = $this->decodeJsonResponse($result);

        self::assertSame([], $payload['data']);
    }
}
