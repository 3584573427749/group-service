<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Commands\UpdateGroupLevelCommand;
use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\ValueObjects\DateTimeValue;

class UpdateGroupLevelHandler extends GroupLevelHandler {
    public function handle(UpdateGroupLevelCommand $command) : GroupLevelDTO {

        $groupLevel = $this->repository->getById($command->id);

        $groupLevel->setName($command->name);
        $groupLevel->setDescription($command->description);
        $groupLevel->setSortOrder($command->sortOrder);
        $groupLevel->setUpdatedAt(new DateTimeValue('now'));

        $this->repository->save($groupLevel);

        $dto = GroupLevelDTO::fromEntity($groupLevel);

        return $dto;
    }
}
