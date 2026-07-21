<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions\GroupLevel;

use App\Application\Handlers\GroupLevel\DeleteGroupLevelHandler;
use App\Domain\ValueObjects\GroupLevelId;
use App\Http\Actions\GroupLevel\DeleteGroupLevelAction;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class DeleteGroupLevelActionTest extends TestCase {
    public function testDeletesGroupLevelAndReturns204() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(DeleteGroupLevelHandler::class);

        $handler
            ->expects(self::once())
            ->method('handle')
            ->with(
                self::callback(
                    fn (GroupLevelId $id) =>
                        $id->toString() === '550e8400-e29b-41d4-a716-446655440000',
                ),
            );

        $action = new DeleteGroupLevelAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
                '/group-levels/550e8400-e29b-41d4-a716-446655440000',
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

    public function testThrowsExceptionForInvalidId() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(DeleteGroupLevelHandler::class);

        $handler
            ->expects(self::never())
            ->method('handle');

        $action = new DeleteGroupLevelAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'DELETE',
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
}
