<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLeader;

use App\Application\Handlers\GroupLeader\GetLeaderGroupsHandler;
use App\Domain\DataTransportObjects\GroupLeaderDTO;
use App\Domain\Entities\User;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class GetLeaderGroupsHandlerTest extends TestCase {
    public function testHandleReturnsGroupLeadersDTO() : void {
        $db = $this->createMock(Connection::class);

        $repository = $this->createMock(GroupLeaderRepository::class);
        $userRepository = $this->createMock(UserRepository::class);

        $userId = new UserId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $userRepository
            ->expects(self::once())
            ->method('getById')
            ->with($userId)
            ->willReturn($this->createUser());

        $repository
            ->expects(self::once())
            ->method('getLeaderGroups')
            ->with($userId)
            ->willReturn([
                $this->createGroupLeader(),
            ]);

        $handler = new GetLeaderGroupsHandler(
            $db,
            $repository,
            $userRepository,
        );

        $result = $handler->handle($userId);

        self::assertCount(1, $result);
        self::assertInstanceOf(
            GroupLeaderDTO::class,
            $result[0],
        );
    }

    public function testHandleReturnsEmptyArrayWhenUserHasNoGroups() : void {
        $db = $this->createMock(Connection::class);

        $repository = $this->createMock(GroupLeaderRepository::class);
        $userRepository = $this->createMock(UserRepository::class);

        $userId = new UserId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $userRepository
            ->expects(self::once())
            ->method('getById')
            ->with($userId)
            ->willReturn($this->createUser());

        $repository
            ->expects(self::once())
            ->method('getLeaderGroups')
            ->with($userId)
            ->willReturn([]);

        $handler = new GetLeaderGroupsHandler(
            $db,
            $repository,
            $userRepository,
        );

        $result = $handler->handle($userId);

        self::assertSame([], $result);
    }

    public function testHandleThrowsNotFoundExceptionWhenUserDoesNotExist() : void {
        $db = $this->createMock(Connection::class);

        $repository = $this->createMock(GroupLeaderRepository::class);
        $userRepository = $this->createMock(UserRepository::class);

        $userId = new UserId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $userRepository
            ->expects(self::once())
            ->method('getById')
            ->with($userId)
            ->willReturn(false);

        $repository
            ->expects(self::never())
            ->method('getLeaderGroups');

        $handler = new GetLeaderGroupsHandler(
            $db,
            $repository,
            $userRepository,
        );

        self::expectException(NotFoundException::class);
        self::expectExceptionMessage(
            'Användaren finns inte',
        );

        $handler->handle($userId);
    }

    private function createUser() : User {
        return new User(
            new UserId(
                '550e8400-e29b-41d4-a716-446655440000',
            ),
            'Anna',
            'Andersson',
            1,
            new DateTimeValue('2026-01-01 10:00:00'),
            null,
        );
    }

    private function createGroupLeader() : GroupLeaderDTO {
        return new GroupLeaderDTO(
            new UserId(
                '550e8400-e29b-41d4-a716-446655440000',
            ),
            'Anna',
            'Andersson',
            new GroupId(
                '660e8400-e29b-41d4-a716-446655440000',
            ),
            'Simskola',
            'Assistent',
        );
    }
}
