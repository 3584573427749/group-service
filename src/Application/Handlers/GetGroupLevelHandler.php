<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\ValueObjects\GroupLevelId;

class GetGroupLevelHandler extends GroupLevelHandler {
    /**
     * @return array<GroupLevelDTO>
     */
    public function getAll() : array {
        $groupLevels = $this->repository->getAll();

        $dto = [];
        foreach ($groupLevels as $groupLevel) {
            $dto[] = GroupLevelDTO::fromEntity($groupLevel);
        }

        return $dto;
    }

    public function getId(GroupLevelId $id) : GroupLevelDTO {
        $groupLevel = $this->repository->getById($id);

        return GroupLevelDTO::fromEntity($groupLevel);
    }
}
