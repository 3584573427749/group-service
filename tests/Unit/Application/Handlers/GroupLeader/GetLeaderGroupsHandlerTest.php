<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLeader;

use App\Application\Handlers\GroupLeader\GetLeaderGroupsHandler;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class GetLeaderGroupsHandlerTest extends TestCase {
    public function testHandleGetsAllGroupsForLeader() : void {
        $repository = $this->createMock(GroupLeaderRepository::class);

        $userId = new UserId('550e8400-e29b-41d4-a716-446655440000', );

        $repository
            ->expects(self::once())
            ->method('getGroups')
            ->with($userId);

        $handler = new class($repository) extends GetLeaderGroupsHandler {
            public function __construct(
                GroupLeaderRepository $repository,
            ) {
                $this->repository = $repository;
            }
        };

        $handler->handle($userId);
    }
}
