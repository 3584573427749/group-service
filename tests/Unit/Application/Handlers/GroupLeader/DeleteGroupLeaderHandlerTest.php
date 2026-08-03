<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLeader;

use App\Application\Handlers\GroupLeader\DeleteGroupLeaderHandler;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class DeleteGroupLeaderHandlerTest extends TestCase {
    public function testHandleDeletesGroupLeader() : void {
        $repository = $this->createMock(GroupLeaderRepository::class);

        $groupId = new GroupId('550e8400-e29b-41d4-a716-446655440000', );

        $userId = new UserId('660e8400-e29b-41d4-a716-446655440000', );

        $repository
            ->expects(self::once())
            ->method('delete')
            ->with($groupId, $userId);

        $handler = new class($repository) extends DeleteGroupLeaderHandler {
            public function __construct(
                GroupLeaderRepository $repository,
            ) {
                $this->repository = $repository;
            }
        };

        $handler->handle($groupId, $userId);
    }
}
