<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions\GroupLeader;

use App\Application\Handlers\GroupLeader\DeleteLeaderGroupsHandler;
use App\Domain\ValueObjects\UserId;
use App\Http\Actions\GroupLeader\DeleteLeaderGroupsAction;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class DeleteLeaderGroupsActionTest extends TestCase {
    public function testDeletesAllGroupsForLeaderAndReturns204() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(DeleteLeaderGroupsHandler::class);

        $handler
            ->expects(self::once())
            ->method('handle')
            ->with(
                self::callback(
                    static fn (UserId $id) : bool =>
                        $id->toString() === '550e8400-e29b-41d4-a716-446655440000',
                ),
            );

        $action = new DeleteLeaderGroupsAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
                '/user/550e8400-e29b-41d4-a716-446655440000/groups',
            )
            ->withAttribute(
                'id',
                '550e8400-e29b-41d4-a716-446655440000',
            );

        $response = (new ResponseFactory())->createResponse();

        $result = $action($request, $response, []);

        self::assertSame(
            204,
            $result->getStatusCode(),
        );
    }
}
