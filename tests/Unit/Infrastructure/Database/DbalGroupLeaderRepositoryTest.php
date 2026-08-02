<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Database;

use App\Application\Commands\GroupLeader\GroupLeaderCommand;
use App\Domain\Entities\GroupLeader;
use App\Domain\Enums\Role;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;
use App\Infrastructure\Database\DbalGroupLeaderRepository;

final class DbalGroupLeaderRepositoryTest extends DatabaseBaseTestCase {
    private DbalGroupLeaderRepository $repository;

    public function testSaveInsertsGroupLeader() : void {
        $groupLeader = $this->createGroupLeader();

        $this->repository->save($groupLeader);

        $row = $this->connection->fetchAssociative(
            'SELECT * FROM group_leaders WHERE group_id = :group_id AND user_id = :user_id',
            [
                'group_id' => $groupLeader->getGroupId()->toString(),
                'user_id' => $groupLeader->getUserId()->toString(),
            ],
        );

        self::assertNotFalse($row);
        self::assertSame(Role::LEADER->value, $row['role'], );
    }

    public function testSaveUpdatesExistingGroupLeader() : void {
        $groupLeader = $this->createGroupLeader();

        $this->connection->insert(
            'group_leaders',
            $groupLeader->asDBRow(),
        );

        $groupLeader->setRole(Role::ASSISTANT);
        $groupLeader->setUpdatedAt(new DateTimeValue('2026-07-01 10:00:00'), );

        $this->repository->save($groupLeader);

        $row = $this->connection->fetchAssociative(
            'SELECT * FROM group_leaders WHERE group_id = :group_id AND user_id = :user_id',
            [
                'group_id' => $groupLeader->getGroupId()->toString(),
                'user_id' => $groupLeader->getUserId()->toString(),
            ],
        );

        self::assertNotFalse($row);

        self::assertSame(Role::ASSISTANT->value, $row['role'], );
    }

    public function testGetReturnsGroupLeader() : void {
        $groupLeader = $this->createGroupLeader();

        $this->connection->insert(
            'group_leaders',
            $groupLeader->asDBRow(),
        );

        $result = $this->repository->get(
            $groupLeader->getGroupId(),
            $groupLeader->getUserId(),
        );

        self::assertInstanceOf(GroupLeader::class, $result, );

        self::assertSame(Role::LEADER, $result->getRole(), );
    }

    public function testGetReturnsFalseWhenRelationDoesNotExist() : void {
        $result = $this->repository->get(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
            new UserId('660e8400-e29b-41d4-a716-446655440000'),
        );

        self::assertFalse($result);
    }

    public function testDeleteRemovesGroupLeader() : void {
        $groupLeader = $this->createGroupLeader();

        $this->connection->insert(
            'group_leaders',
            $groupLeader->asDBRow(),
        );

        $this->repository->delete(
            $groupLeader->getGroupId(),
            $groupLeader->getUserId(),
        );

        $row = $this->connection->fetchAssociative(
            'SELECT * FROM group_leaders WHERE group_id = :group_id AND user_id = :user_id',
            [
                'group_id' => $groupLeader->getGroupId()->toString(),
                'user_id' => $groupLeader->getUserId()->toString(),
            ],
        );

        self::assertFalse($row);
    }

    public function testDeleteByUserRemovesAllRelationsForUser() : void {
        $groupLeader = $this->createGroupLeader();

        $this->connection->insert(
            'group_leaders',
            $groupLeader->asDBRow(),
        );

        $this->repository->deleteByUser(
            $groupLeader->getUserId(),
        );

        $count = $this->connection->fetchOne(
            'SELECT COUNT(*) FROM group_leaders',
        );

        self::assertSame(0, (int) $count);
    }

    public function testDeleteByGroupRemovesAllRelationsForGroup() : void {
        $groupLeader = $this->createGroupLeader();

        $this->connection->insert(
            'group_leaders',
            $groupLeader->asDBRow(),
        );

        $this->repository->deleteByGroup(
            $groupLeader->getGroupId(),
        );

        $count = $this->connection->fetchOne(
            'SELECT COUNT(*) FROM group_leaders',
        );

        self::assertSame(0, (int) $count);
    }

    private function createGroupLeader() : GroupLeader {
        return GroupLeader::fromCommand(
            GroupLeaderCommand::fromRequest([
                'groupId' => '550e8400-e29b-41d4-a716-446655440000',
                'userId' => '660e8400-e29b-41d4-a716-446655440000',
                'role' => Role::LEADER->value,
            ]),
        );
    }

    protected function setUp() : void {
        parent::setUp();

        $this->loadSchema('users');
        $this->loadSchema('groups');
        $this->loadSchema('group_leaders');

        $this->repository = new DbalGroupLeaderRepository(
            $this->connection,
        );
    }
}
