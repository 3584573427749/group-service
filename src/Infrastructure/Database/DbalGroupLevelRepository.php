<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use App\Domain\Entities\GroupLevel;
use App\Domain\Repositories\GroupLevelRepository;
use App\Domain\ValueObjects\GroupLevelId;

class DbalGroupLevelRepository extends AbstractDbRepository implements GroupLevelRepository {
    private const TABLE = 'group_levels';

    public function save(GroupLevel $groupLevel) : void {
        if ($groupLevel->getUpdatedAt() !== null) {
            $this->connection->update(self::TABLE, $groupLevel->asDBRow(), ['id' => $groupLevel->getId()->toString()]);
        } else {
            $this->connection->insert(self::TABLE, $groupLevel->asDBRow());
        }
    }

    /**
     * @inheritDoc
     */
    public function getAll() : array {
        $rows = $this->connection->executeQuery('SELECT * FROM ' . self::TABLE . ' WHERE deleted_at IS NULL')
            ->fetchAllAssociative();

        return array_map(fn ($row) => GroupLevel::fromDBRow($row), $rows);
    }

    public function getById(GroupLevelId $id) : GroupLevel {
        $row = $this->connection->executeQuery('SELECT * FROM ' . self::TABLE . ' WHERE id = ? ', [$id->toString()])
            ->fetchAssociative();

        if ($row === false) {
            throw new \InvalidArgumentException('GroupLevel saknas');
        }

        return GroupLevel::fromDBRow($row);
    }

    public function delete(GroupLevelId $id) : void {
        $this->connection->delete(self::TABLE, ['id' => $id->toString()]);
    }
}
