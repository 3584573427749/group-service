<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Domain\DataTransportObjects\GroupLevelDTO;

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
}
