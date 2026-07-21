<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLevel;

use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\ValueObjects\GroupLevelId;

class GetGroupLevelHandler extends GroupLevelHandler {
    /**
     * @return array<GroupLevelDTO>
     */
    public function getAll() : array {

        return array_map(
            fn ($groupLevel) => GroupLevelDTO::fromEntity($groupLevel),
            $this->repository->getAll(),
        );
    }

    public function getId(GroupLevelId $id) : GroupLevelDTO {
        $groupLevel = $this->repository->getById($id);

        return GroupLevelDTO::fromEntity($groupLevel);
    }
}
