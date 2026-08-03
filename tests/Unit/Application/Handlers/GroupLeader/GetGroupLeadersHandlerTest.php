<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLeader;

use App\Application\Handlers\GroupLeader\GetGroupLeadersHandler;
use App\Domain\Entities\Group;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\Repositories\GroupRepository;
use App\Domain\ValueObjects\GroupId;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class GetGroupLeadersHandlerTest extends TestCase {
    public function testHandleGetsAllLeadersForGroup() : void {
        $db = $this->createMock(Connection::class);
        $repository = $this->createMock(GroupLeaderRepository::class);
        $groupRepository = $this->createMock(GroupRepository::class);

        $groupId = new GroupId('550e8400-e29b-41d4-a716-446655440000', );

        $repository
            ->expects(self::once())
            ->method('getUsers')
            ->with($groupId);

        $group = $this->createMock(Group::class);

        $group
            ->expects(self::once())
            ->method('getActive')
            ->willReturn(1);

        $groupRepository
            ->expects(self::once())
            ->method('getById')
            ->with($groupId)
            ->willReturn($group);
        $handler = new GetGroupLeadersHandler(
            $db,
            $repository,
            $groupRepository,
        );

        $handler->handle($groupId);
    }

    public function testHandleThrowsNotFoundExceptionWhenGroupIsInactive() : void {
        $db = $this->createMock(Connection::class);

        $repository = $this->createMock(GroupLeaderRepository::class);
        $groupRepository = $this->createMock(GroupRepository::class);

        $group = $this->createMock(Group::class);

        $group
            ->expects(self::once())
            ->method('getActive')
            ->willReturn(0);

        $groupRepository
            ->expects(self::once())
            ->method('getById')
            ->willReturn($group);

        $repository
            ->expects(self::never())
            ->method('getUsers');

        $handler = new GetGroupLeadersHandler(
            $db,
            $repository,
            $groupRepository,
        );

        self::expectException(NotFoundException::class);
        self::expectExceptionMessage('Gruppen är inaktiv');

        $handler->handle(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
        );
    }
}
