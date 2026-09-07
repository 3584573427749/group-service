<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use App\Domain\DataTransportObjects\GroupLeaderDTO;
use App\Domain\Entities\GroupLeader;
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
            $this->connection->insert(self::TABLE, $groupLeader->asDBRow());
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
    public function getGroupLeaders(GroupId $id) : array {
        $rows = $this->connection->executeQuery(
            'SELECT users.id as user_id, first_name, last_name, 
       group.id as group_id, group.name, group_leaders.role FROM users
    INNER JOIN ' . self::TABLE . ' ON users.id = ' . self::TABLE . '.user_id
    INNER JOIN groups ON groups.id = ' . self::TABLE . '.group_id
    WHERE ' . self::TABLE . '.group_id = ?',
            [$id->toString()],
        )
            ->fetchAllAssociative();

        return array_map(fn ($row) => GroupLeaderDTO::fromDBRow($row), $rows);
    }

    /**
     * @inheritDoc
     */
    public function getLeaderGroups(UserId $id) : array {
        $rows = $this->connection->executeQuery(
            'SELECT group.id as group_id, group.name, 
       user.id as user_id, user.first_name, user.last_name, 
      ' . self::TABLE . '.role FROM groups
    INNER JOIN ' . self::TABLE . ' ON groups.id = ' . self::TABLE . '.group_id
    INNER JOIN users ON users.id = ' . self::TABLE . '.user_id
    WHERE ' . self::TABLE . '.user_id = ?',
            [$id->toString()],
        )
            ->fetchAllAssociative();

        return array_map(fn ($row) => GroupLeaderDTO::fromDBRow($row), $rows);
    }

    public function delete(GroupId $groupId, UserId $userId) : void {
        $this->connection->delete(
            self::TABLE,
            ['group_id' => $groupId->toString(), 'user_id' => $userId->toString()],
        );
    }

    public function deleteByUser(UserId $id) : void {
        $this->connection->delete(self::TABLE, ['user_id' => $id->toString()]);
    }

    public function deleteByGroup(GroupId $id) : void {
        $this->connection->delete(self::TABLE, ['group_id' => $id->toString()]);
    }
}
