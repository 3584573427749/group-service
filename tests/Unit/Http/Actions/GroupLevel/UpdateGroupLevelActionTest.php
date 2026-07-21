<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions\GroupLevel;

use App\Application\Commands\GroupLevel\UpdateGroupLevelCommand;
use App\Application\Handlers\GroupLevel\UpdateGroupLevelHandler;
use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\Entities\GroupLevel;
use App\Domain\Exception\ValidationException;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;
use App\Http\Actions\GroupLevel\UpdateGroupLevelAction;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class UpdateGroupLevelActionTest extends TestCase {
    public function testUpdatesGroupLevelAndReturns200WhenRequestBodyIsValid() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $groupLevel = new GroupLevel(
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Pingvinen',
            'Kan simma själv',
            2,
            new DateTimeValue('2026-06-10T10:00:00+00:00'),
            new DateTimeValue('2026-06-11T10:00:00+00:00'),
        );

        $dto = GroupLevelDTO::fromEntity($groupLevel);

        $handler = $this->createMock(UpdateGroupLevelHandler::class);

        $handler
            ->expects(self::once())
            ->method('handle')
            ->with(self::isInstanceOf(UpdateGroupLevelCommand::class))
            ->willReturn($dto);

        $action = new UpdateGroupLevelAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/550e8400-e29b-41d4-a716-446655440000',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            )
            ->withParsedBody([
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'Pingvinen',
                'description' => 'Kan simma själv',
                'sortOrder' => 2,
            ]);

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

    public function testThrowsValidationExceptionWhenRequestIsInvalid() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(UpdateGroupLevelHandler::class);

        $handler
            ->expects(self::never())
            ->method('handle');

        $action = new UpdateGroupLevelAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/550e8400-e29b-41d4-a716-446655440000',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            )
            ->withParsedBody([
                'id' => '660e8400-e29b-41d4-a716-446655440000',
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
