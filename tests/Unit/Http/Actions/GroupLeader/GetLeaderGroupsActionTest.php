<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions\GroupLeader;

use App\Application\Handlers\GroupLeader\GetLeaderGroupsHandler;
use App\Domain\ValueObjects\UserId;
use App\Http\Actions\GroupLeader\GetLeaderGroupsAction;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class GetLeaderGroupsActionTest extends TestCase {
    public function testReturnsGroupsForLeader() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(GetLeaderGroupsHandler::class);

        $handler
            ->expects(self::once())
            ->method('handle')
            ->with(
                self::callback(
                    static fn (UserId $id) : bool =>
                        $id->toString() === '550e8400-e29b-41d4-a716-446655440000',
                ),
            )
            ->willReturn([
                [
                    'id' => '660e8400-e29b-41d4-a716-446655440000',
                    'name' => 'Tävlingsgrupp A',
                ],
                [
                    'id' => '770e8400-e29b-41d4-a716-446655440000',
                    'name' => 'Tävlingsgrupp B',
                ],
            ]);

        $action = new GetLeaderGroupsAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'GET',
                '/leaders/550e8400-e29b-41d4-a716-446655440000/groups',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = (new ResponseFactory())->createResponse();

        $result = $action($request, $response, []);

        self::assertSame(
            200,
            $result->getStatusCode(),
        );

        $payload = $this->decodeJsonResponse($result);

        self::assertSame(200, $payload['statusCode']);

        self::assertCount(
            2,
            $payload['data'],
        );
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
