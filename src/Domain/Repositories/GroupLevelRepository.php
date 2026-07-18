<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\GroupLevel;
use App\Domain\ValueObjects\GroupLevelId;

interface GroupLevelRepository {
    public function save(GroupLevel $groupLevel) : void;

    /**
     * @return array<GroupLevel>
     */
    public function getAll() : array;

    public function getById(GroupLevelId $id) : GroupLevel;

    public function delete(GroupLevelId $id) : void;
}
