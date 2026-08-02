<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use App\Domain\Entities\Group;
use App\Domain\Entities\GroupLeader;
use App\Domain\Entities\User;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;

class DbalGroupLeaderRepository extends AbstractDbRepository implements GroupLeaderRepository {
    private const TABLE = 'group_leaders';

    public function save(GroupLeader $groupLeader) : void {
        if ($groupLeader->getUpdatedAt() !== null) {
            $this->connection->update(
                self::TABLE,
                $groupLeader->asDBRow(),
                ['group_id' => $groupLeader->getGroupId()->toString(), 'user_id' => $groupLeader->getUserId()->toString()],
            );
        } else {
            $this->connection->insert(self::TABLE, $groupLeader -> asDBRow());
        }
    }

    public function get(GroupId $groupId, UserId $userId) : GroupLeader|false {
        $row = $this->connection->executeQuery(
            'SELECT * FROM ' . self::TABLE . ' WHERE group_id = ? AND user_id = ?',
            [$groupId->toString(), $userId->toString()],
        )
        ->fetchAssociative();

        if ($row === false) {
            return false;
        }

        return GroupLeader::fromDBRow($row);
    }

    /**
     * @inheritDoc
     */
    public function getUsers(GroupId $id) : array {
        $rows = $this->connection->executeQuery(
            'SELECT * FROM users
    INNER JOIN ' . self::TABLE . ' ON users.id = ' . self::TABLE . '.user_id
    WHERE ' . self::TABLE . '.group_id = ?',
            [$id->toString()],
        )
            ->fetchAllAssociative();

        return array_map(fn ($row) => User::fromDBRow($row), $rows);
    }

    /**
     * @inheritDoc
     */
    public function getGroups(UserId $id) : array {
        $rows = $this->connection->executeQuery(
            'SELECT * FROM groups
    INNER JOIN ' . self::TABLE . ' ON groups.id = ' . self::TABLE . '.group_id
    WHERE ' . self::TABLE . '.user_id = ?',
            [$id->toString()],
        )
            ->fetchAllAssociative();

        return array_map(fn ($row) => Group::fromDBRow($row), $rows);
    }

    public function delete(GroupLeader $groupLeader) : void {
        $this->connection->delete(
            self::TABLE,
            ['group_id' => $groupLeader->getGroupId()->toString(), 'user_id' => $groupLeader->getUserId()->toString()],
        );
    }

    public function deleteByUser(UserId $id) : void {
        $this->connection->delete(self::TABLE, ['user_id' => $id->toString()]);
    }

    public function deleteByGroup(GroupId $id) : void {
        $this->connection->delete(self::TABLE, ['group_id' => $id->toString()]);
    }
}
