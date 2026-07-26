<?php

declare(strict_types=1);

namespace Http\Actions\Group;

use App\Application\Commands\Group\CreateGroupCommand;
use App\Application\Handlers\Group\CreateGroupHandler;
use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\Entities\Group;
use App\Domain\Enums\Venue;
use App\Domain\Exception\ValidationException;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;
use App\Http\Actions\Group\CreateGroupAction;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class CreateGroupActionTest extends TestCase {
    public function testCreatesGroupAndReturns201WhenRequestBodyIsValid() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $group = new Group(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            Venue::ALANDS_IDROTTCENTER,
            1,
            1,
            new DateTimeValue('2026-06-10T10:00:00+00:00'),
            null,
        );

        $dto = GroupDTO::fromEntity($group);

        $handler = $this->createMock(CreateGroupHandler::class);

        $handler
            ->expects(self::once())
            ->method('handle')
            ->with(self::isInstanceOf(CreateGroupCommand::class))
            ->willReturn($dto);

        $action = new CreateGroupAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/groups')
            ->withParsedBody([
                'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'venue' => Venue::ALANDS_IDROTTCENTER->value,
                'active' => 1,
                'competitive' => 1,
            ]);

        $response = (new ResponseFactory())->createResponse();

        $result = $action($request, $response, []);

        self::assertSame(201, $result->getStatusCode());

        $payload = $this->decodeJsonResponse($result);

        self::assertSame(201, $payload['statusCode']);

        self::assertArrayHasKey('data', $payload);

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $payload['data']['id']);
        self::assertSame('Baddaren', $payload['data']['name']);
        self::assertSame('För nybörjare', $payload['data']['description']);
        self::assertSame(1, $payload['data']['active']);
        self::assertSame(1, $payload['data']['competitive']);
        self::assertNull($payload['data']['updatedAt']);
    }

    public function testThrowsExceptionWhenRequestBodyIsInvalid() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(CreateGroupHandler::class);

        $handler
            ->expects(self::never())
            ->method('handle');

        $action = new CreateGroupAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest('POST', '/groups')
            ->withParsedBody([
                'groupLevelId' => 'invalid',
                'name' => 'A',
                'active' => 'abc',
                'competitive' => 'abc',
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
        $body = (string)$response->getBody();

        self::assertNotSame('', $body);

        $decoded = json_decode($body, true);

        self::assertIsArray($decoded);

        return $decoded;
    }
}
