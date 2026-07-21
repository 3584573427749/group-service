<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions\GroupLevel;

use App\Application\Commands\GroupLevel\CreateGroupLevelCommand;
use App\Application\Handlers\GroupLevel\CreateGroupLevelHandler;
use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\Entities\GroupLevel;
use App\Domain\Exception\ValidationException;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;
use App\Http\Actions\GroupLevel\CreateGroupLevelAction;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class CreateGroupLevelActionTest extends TestCase {
    public function testCreatesGroupLevelAndReturns201WhenRequestBodyIsValid() : void {
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

        $handler = $this->createMock(CreateGroupLevelHandler::class);

        $handler
            ->expects(self::once())
            ->method('handle')
            ->with(self::isInstanceOf(CreateGroupLevelCommand::class))
            ->willReturn($dto);

        $action = new CreateGroupLevelAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/group-levels')
            ->withParsedBody([
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'sortOrder' => 1,
            ]);

        $response = (new ResponseFactory())->createResponse();

        $result = $action($request, $response, []);

        self::assertSame(201, $result->getStatusCode());

        $payload = $this->decodeJsonResponse($result);

        self::assertSame(201, $payload['statusCode']);

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

    public function testThrowsExceptionWhenRequestBodyIsInvalid() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(CreateGroupLevelHandler::class);

        $handler
            ->expects(self::never())
            ->method('handle');

        $action = new CreateGroupLevelAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/group-levels')
            ->withParsedBody([
                'name' => 'A',
                'sortOrder' => 'abc',
            ]);

        $response = (new ResponseFactory())->createResponse();

        self::expectException(ValidationException::class);
        self::expectExceptionMessage('Felaktig indata');

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
