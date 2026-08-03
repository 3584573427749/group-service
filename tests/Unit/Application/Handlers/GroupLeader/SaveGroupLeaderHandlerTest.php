<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLeader;

use App\Application\Commands\GroupLeader\GroupLeaderCommand;
use App\Application\Handlers\GroupLeader\SaveGroupLeaderHandler;
use App\Domain\Entities\GroupLeader;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\Repositories\GroupRepository;
use App\Domain\Repositories\UserRepository;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class SaveGroupLeaderHandlerTest extends TestCase {
    public function testCreatesNewGroupLeaderWhenRelationDoesNotExist() : void {
        $db = $this->createMock(Connection::class);

        $repository = $this->createMock(GroupLeaderRepository::class);
        $groupRepository = $this->createMock(GroupRepository::class);
        $userRepository = $this->createMock(UserRepository::class);

        $command = GroupLeaderCommand::fromRequest([
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => 'Ledare',
        ]);

        $groupRepository
            ->expects(self::once())
            ->method('getById');

        $userRepository
            ->expects(self::once())
            ->method('getById');

        $repository
            ->expects(self::once())
            ->method('get')
            ->willReturn(false);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(
                self::isInstanceOf(GroupLeader::class),
            );

        $handler = new SaveGroupLeaderHandler(
            $db,
            $repository,
            $groupRepository,
            $userRepository,
        );

        $handler->handle($command);
    }

    public function testUpdatesExistingGroupLeaderRole() : void {
        $db = $this->createMock(Connection::class);

        $repository = $this->createMock(GroupLeaderRepository::class);
        $groupRepository = $this->createMock(GroupRepository::class);
        $userRepository = $this->createMock(UserRepository::class);

        $command = GroupLeaderCommand::fromRequest([
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => 'Ledare',
        ]);

        $existing = GroupLeader::fromCommand(
            GroupLeaderCommand::fromRequest([
                'groupId' => '550e8400-e29b-41d4-a716-446655440000',
                'userId' => '660e8400-e29b-41d4-a716-446655440000',
                'role' => 'Assistent',
            ]),
        );

        $groupRepository
            ->expects(self::once())
            ->method('getById');

        $userRepository
            ->expects(self::once())
            ->method('getById');

        $repository
            ->expects(self::once())
            ->method('get')
            ->willReturn($existing);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(
                self::callback(
                    static fn (GroupLeader $groupLeader) : bool =>
                        $groupLeader->getRole()->value === 'Ledare' && $groupLeader->getUpdatedAt() !== null,
                ),
            );

        $handler = new SaveGroupLeaderHandler(
            $db,
            $repository,
            $groupRepository,
            $userRepository,
        );

        $handler->handle($command);
    }

    public function testThrowsExceptionWhenGroupDoesNotExist() : void {
        $db = $this->createMock(Connection::class);

        $repository = $this->createMock(GroupLeaderRepository::class);
        $groupRepository = $this->createMock(GroupRepository::class);
        $userRepository = $this->createMock(UserRepository::class);

        $command = GroupLeaderCommand::fromRequest([
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => 'Ledare',
        ]);

        $groupRepository
            ->expects(self::once())
            ->method('getById')
            ->willThrowException(
                new \RuntimeException('Group not found'),
            );

        $repository
            ->expects(self::never())
            ->method('save');

        $handler = new SaveGroupLeaderHandler(
            $db,
            $repository,
            $groupRepository,
            $userRepository,
        );

        self::expectException(\RuntimeException::class);

        $handler->handle($command);
    }

    public function testThrowsExceptionWhenUserDoesNotExist() : void {
        $db = $this->createMock(Connection::class);

        $repository = $this->createMock(GroupLeaderRepository::class);
        $groupRepository = $this->createMock(GroupRepository::class);
        $userRepository = $this->createMock(UserRepository::class);

        $command = GroupLeaderCommand::fromRequest([
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => 'Leader',
        ]);

        $groupRepository
            ->expects(self::once())
            ->method('getById');

        $userRepository
            ->expects(self::once())
            ->method('getById')
            ->willThrowException(
                new \RuntimeException('User not found'),
            );

        $repository
            ->expects(self::never())
            ->method('save');

        $handler = new SaveGroupLeaderHandler(
            $db,
            $repository,
            $groupRepository,
            $userRepository,
        );

        self::expectException(\RuntimeException::class);

        $handler->handle($command);
    }
}
