<?php

declare(strict_types=1);

namespace Http\Actions\Group;

use App\Application\Handlers\Group\GetGroupHandler;
use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\Entities\Group;
use App\Domain\Enums\Venue;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;
use App\Http\Actions\Group\GetGroupAction;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class GetGroupActionTest extends TestCase {
    public function testReturnsGroup() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $group = new Group(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
            new GroupLevelId('660e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            Venue::ALANDS_IDROTTCENTER,
            1,
            1,
            new DateTimeValue('2026-06-10T10:00:00+00:00'),
            null,
        );

        $dto = GroupDTO::fromEntity($group);

        $handler = $this->createMock(GetGroupHandler::class);

        $handler
            ->expects(self::once())
            ->method('getId')
            ->with(
                self::callback(
                    fn (GroupId $id) => $id->toString() === '550e8400-e29b-41d4-a716-446655440000',
                ),
            )
            ->willReturn($dto);

        $action = new GetGroupAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'GET',
                '/group/550e8400-e29b-41d4-a716-446655440000',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = (new ResponseFactory())->createResponse();

        $result = $action($request, $response, []);

        self::assertSame(200, $result->getStatusCode());

        $payload = $this->decodeJsonResponse($result);

        self::assertSame(200, $payload['statusCode']);

        self::assertArrayHasKey('data', $payload);

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $payload['data']['id']);
        self::assertSame('660e8400-e29b-41d4-a716-446655440000', $payload['data']['groupLevelId']);
        self::assertSame('Baddaren', $payload['data']['name']);
        self::assertSame('För nybörjare', $payload['data']['description']);
        self::assertSame('Ålands Idrottscenter', $payload['data']['venue']);
        self::assertSame(1, $payload['data']['active']);
        self::assertSame(1, $payload['data']['competitive']);
    }

    public function testThrowsExceptionForInvalidId() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(GetGroupHandler::class);

        $handler
            ->expects(self::never())
            ->method('getId');

        $action = new GetGroupAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'GET',
                '/group/invalid',
            )
            ->withAttribute(
                'id',
                'invalid',
            );

        $response = (new ResponseFactory())->createResponse();

        self::expectException(\InvalidArgumentException::class);

        $action($request, $response, []);
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
}
