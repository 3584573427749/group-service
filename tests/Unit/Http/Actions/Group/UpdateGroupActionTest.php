<?php

declare(strict_types=1);

namespace Http\Actions\Group;

use App\Application\Commands\Group\UpdateGroupCommand;
use App\Application\Handlers\Group\UpdateGroupHandler;
use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\Entities\Group;
use App\Domain\Enums\Venue;
use App\Domain\Exception\ValidationException;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;
use App\Http\Actions\Group\UpdateGroupAction;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class UpdateGroupActionTest extends TestCase {
    public function testUpdatesGroupAndReturns200WhenRequestBodyIsValid() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $group = new Group(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
            new GroupLevelId('660e8400-e29b-41d4-a716-446655440000'),
            'Pingvinen',
            'Kan simma själv',
            Venue::ALANDS_IDROTTCENTER,
            1,
            1,
            new DateTimeValue('2026-06-10T10:00:00+00:00'),
            new DateTimeValue('2026-06-11T10:00:00+00:00'),
        );

        $dto = GroupDTO::fromEntity($group);

        $handler = $this->createMock(UpdateGroupHandler::class);

        $handler
            ->expects(self::once())
            ->method('handle')
            ->with(self::isInstanceOf(UpdateGroupCommand::class))
            ->willReturn($dto);

        $action = new UpdateGroupAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group/550e8400-e29b-41d4-a716-446655440000',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            )
            ->withParsedBody([
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'groupLevelId' => '660e8400-e29b-41d4-a716-446655440000',
                'name' => 'Pingvinen',
                'description' => 'Kan simma själv',
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 1,
            ]);

        $response = (new ResponseFactory())->createResponse();

        $result = $action($request, $response, []);

        self::assertSame(200, $result->getStatusCode());

        $payload = $this->decodeJsonResponse($result);

        self::assertSame(200, $payload['statusCode']);

        self::assertArrayHasKey('data', $payload);

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $payload['data']['id'], );
        self::assertSame('Pingvinen', $payload['data']['name'], );
        self::assertSame('Kan simma själv', $payload['data']['description'], );
        self::assertSame('Ålands Idrottscenter', $payload['data']['venue'], );
        self::assertSame(1, $payload['data']['active'], );
        self::assertSame(1, $payload['data']['competitive'], );
    }

    public function testThrowsValidationExceptionWhenRequestIsInvalid() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(UpdateGroupHandler::class);

        $handler
            ->expects(self::never())
            ->method('handle');

        $action = new UpdateGroupAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group/550e8400-e29b-41d4-a716-446655440000',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            )
            ->withParsedBody([
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'groupLevelId' => '660e8400-e29b-41d4-a716-446655440000',
                'name' => 'A',
                'venue' => 'A',
                'active' => 2,
                'competitive' => -1,
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
