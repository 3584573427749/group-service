<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Actions\GroupLeader;

use App\Application\Commands\GroupLeader\GroupLeaderCommand;
use App\Application\Handlers\GroupLeader\SaveGroupLeaderHandler;
use App\Domain\Exception\ValidationException;
use App\Http\Actions\GroupLeader\UpsertGroupLeaderAction;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

final class UpsertGroupLeaderActionTest extends TestCase {
    public function testSavesGroupLeaderAndReturns204WhenRequestBodyIsValid() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(SaveGroupLeaderHandler::class);

        $handler
            ->expects(self::once())
            ->method('handle')
            ->with(
                self::isInstanceOf(GroupLeaderCommand::class),
            );

        $action = new UpsertGroupLeaderAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'POST',
                '/groups/550e8400-e29b-41d4-a716-446655440000/users',
            )
            ->withAttribute('id', '550e8400-e29b-41d4-a716-446655440000')
            ->withParsedBody([
                'groupId' => '550e8400-e29b-41d4-a716-446655440000',
                'userId' => '660e8400-e29b-41d4-a716-446655440000',
                'role' => 'Ledare',
            ]);

        $response = (new ResponseFactory())->createResponse();

        $result = $action($request, $response, []);

        self::assertSame(
            204,
            $result->getStatusCode(),
        );
    }

    public function testThrowsValidationExceptionWhenRequestBodyIsInvalid() : void {
        $logger = $this->createMock(LoggerInterface::class);

        $handler = $this->createMock(SaveGroupLeaderHandler::class);

        $handler
            ->expects(self::never())
            ->method('handle');

        $action = new UpsertGroupLeaderAction(
            $logger,
            $handler,
        );

        $request = (new ServerRequestFactory())
            ->createServerRequest(
                'POST',
                '/group-leaders',
            )
            ->withParsedBody([
                'groupId' => '',
                'userId' => '',
                'role' => '',
            ]);

        $response = (new ResponseFactory())->createResponse();

        self::expectException(ValidationException::class);
        self::expectExceptionMessage('Felaktig indata');

        $action($request, $response, []);
    }
}
