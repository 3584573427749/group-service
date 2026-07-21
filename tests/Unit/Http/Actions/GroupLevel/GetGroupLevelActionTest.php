<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions\GroupLevel;

use App\Application\Handlers\GroupLevel\GetGroupLevelHandler;
use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\Entities\GroupLevel;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;
use App\Http\Actions\GroupLevel\GetGroupLevelAction;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class GetGroupLevelActionTest extends TestCase {
    public function testReturnsGroupLevel() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $groupLevel = new GroupLevel(
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            1,
            new DateTimeValue('2026-06-10T10:00:00+00:00'),
            null,
        );

        $dto = GroupLevelDTO::fromEntity($groupLevel);

        $handler = $this->createMock(GetGroupLevelHandler::class);

        $handler
            ->expects(self::once())
            ->method('getId')
            ->with(
                self::callback(
                    fn (GroupLevelId $id) =>
                        $id->toString() === '550e8400-e29b-41d4-a716-446655440000',
                ),
            )
            ->willReturn($dto);

        $action = new GetGroupLevelAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'GET',
                '/group-levels/550e8400-e29b-41d4-a716-446655440000',
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

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $payload['data']['id'],
        );

        self::assertSame(
            'Baddaren',
            $payload['data']['name'],
        );

        self::assertSame(
            'För nybörjare',
            $payload['data']['description'],
        );

        self::assertSame(
            1,
            $payload['data']['sortOrder'],
        );
    }

    public function testThrowsExceptionForInvalidId() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(GetGroupLevelHandler::class);

        $handler
            ->expects(self::never())
            ->method('getId');

        $action = new GetGroupLevelAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'GET',
                '/group-levels/invalid',
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
        $body = (string) $response->getBody();

        self::assertNotSame('', $body);

        $decoded = json_decode($body, true);

        self::assertIsArray($decoded);

        return $decoded;
    }
}
