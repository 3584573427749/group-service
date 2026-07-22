<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Group;
use App\Domain\ValueObjects\GroupId;

interface GroupRepository {
    public function save(Group $group) : void;

    /**
     * @return array<Group>
     */
    public function getAll() : array;

    public function getById(GroupId $id) : Group;

    public function delete(GroupId $id) : void;
}
