<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLeader;

use App\Application\Handlers\GroupLeader\GetGroupLeadersHandler;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\ValueObjects\GroupId;
use PHPUnit\Framework\TestCase;

final class GetGroupLeadersHandlerTest extends TestCase {
    public function testHandleGetsAllLeadersForGroup() : void {
        $repository = $this->createMock(GroupLeaderRepository::class);

        $groupId = new GroupId('550e8400-e29b-41d4-a716-446655440000', );

        $repository
            ->expects(self::once())
            ->method('getUsers')
            ->with($groupId);

        $handler = new class($repository) extends GetGroupLeadersHandler {
            public function __construct(
                GroupLeaderRepository $repository,
            ) {
                $this->repository = $repository;
            }
        };

        $handler->handle($groupId);
    }
}
