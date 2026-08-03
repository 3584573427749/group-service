<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLeader;

use App\Application\Handlers\GroupLeader\DeleteGroupLeadersHandler;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\ValueObjects\GroupId;
use PHPUnit\Framework\TestCase;

final class DeleteGroupLeadersHandlerTest extends TestCase {
    public function testHandleDeletesAllLeadersForGroup() : void {
        $repository = $this->createMock(GroupLeaderRepository::class);

        $groupId = new GroupId('550e8400-e29b-41d4-a716-446655440000', );

        $repository
            ->expects(self::once())
            ->method('deleteByGroup')
            ->with($groupId);

        $handler = new class($repository) extends DeleteGroupLeadersHandler {
            public function __construct(
                GroupLeaderRepository $repository,
            ) {
                $this->repository = $repository;
            }
        };

        $handler->handle($groupId);
    }
}
