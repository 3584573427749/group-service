<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions\GroupLeader;

use App\Application\Handlers\GroupLeader\DeleteGroupLeadersHandler;
use App\Domain\ValueObjects\GroupId;
use App\Http\Actions\GroupLeader\DeleteGroupLeadersAction;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class DeleteGroupLeadersActionTest extends TestCase {
    public function testDeletesAllGroupLeadersAndReturns204() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(DeleteGroupLeadersHandler::class);

        $handler
            ->expects(self::once())
            ->method('handle')
            ->with(
                self::callback(
                    static fn (GroupId $id) : bool =>
                        $id->toString() === '550e8400-e29b-41d4-a716-446655440000',
                ),
            );

        $action = new DeleteGroupLeadersAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
                '/groups/550e8400-e29b-41d4-a716-446655440000/users',
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
