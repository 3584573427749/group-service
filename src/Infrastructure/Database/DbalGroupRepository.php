<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use App\Domain\Entities\Group;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repositories\GroupRepository;
use App\Domain\ValueObjects\GroupId;
use Doctrine\DBAL\Exception;

class DbalGroupRepository extends AbstractDbRepository implements GroupRepository {
    private const TABLE = 'groups';

    public function save(Group $group) : void {
        if ($group->getUpdatedAt() !== null) {
            $this->connection->update(self::TABLE, $group->asDBRow(), ['id' => $group->getId()->toString()]);
        } else {
            $this->connection->insert(self::TABLE, $group->asDBRow());
        }
    }

    /**
     * @inheritDoc
     */
    public function getAll() : array {
        $rows = $this->connection->executeQuery('SELECT * FROM ' . self::TABLE)
            ->fetchAllAssociative();

        return array_map(fn ($row) => Group::fromDBRow($row), $rows);
    }

    public function getById(GroupId $id) : Group {
        $row = $this->connection->executeQuery('SELECT * FROM ' . self::TABLE . ' WHERE id = ? ', [$id->toString()])
            ->fetchAssociative();

        if ($row === false) {
            throw new NotFoundException('Group saknas');
        }

        return Group::fromDBRow($row);
    }

    /**
     * @throws Exception
     */
    public function delete(GroupId $id) : void {
        $affectedRows = $this->connection->delete(self::TABLE, ['id' => $id->toString()]);

        if ($affectedRows === 0) {
            throw new NotFoundException('Group saknas');
        }
    }
}
