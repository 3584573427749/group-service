<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions\GroupLevel;

use App\Application\Commands\GroupLevel\UpdateGroupLevelSortOrderCommand;
use App\Application\Handlers\GroupLevel\UpdateGroupLevelSortOrderHandler;
use App\Domain\Exception\ValidationException;
use App\Http\Actions\GroupLevel\UpdateGroupLevelSortOrderAction;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class UpdateGroupLevelSortOrderActionTest extends TestCase {
    public function testUpdatesSortOrderAndReturns204() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(
            UpdateGroupLevelSortOrderHandler::class,
        );

        $handler
            ->expects(self::once())
            ->method('handle')
            ->with(
                self::isInstanceOf(
                    UpdateGroupLevelSortOrderCommand::class,
                ),
            );

        $action = new UpdateGroupLevelSortOrderAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/sortorder',
            )
            ->withParsedBody([
                [
                    'id' => '550e8400-e29b-41d4-a716-446655440000',
                    'sortOrder' => 1,
                ],
                [
                    'id' => '660e8400-e29b-41d4-a716-446655440000',
                    'sortOrder' => 2,
                ],
            ]);

        $response = (new ResponseFactory())
            ->createResponse();

        $result = $action($request, $response, []);

        self::assertSame(
            204,
            $result->getStatusCode(),
        );
    }

    public function testThrowsValidationExceptionWhenRequestIsInvalid() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(
            UpdateGroupLevelSortOrderHandler::class,
        );

        $handler
            ->expects(self::never())
            ->method('handle');

        $action = new UpdateGroupLevelSortOrderAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'PUT',
                '/group-levels/sortorder',
            )
            ->withParsedBody([]);

        $response = (new ResponseFactory())
            ->createResponse();

        self::expectException(
            ValidationException::class,
        );

        self::expectExceptionMessage(
            'Felaktig indata',
        );

        $action($request, $response, []);
    }
}
