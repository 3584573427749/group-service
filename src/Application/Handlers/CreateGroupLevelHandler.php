<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Commands\CreateGroupLevelCommand;
use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\Entities\GroupLevel;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;

class CreateGroupLevelHandler extends GroupLevelHandler {
    public function handle(CreateGroupLevelCommand $command) : GroupLevelDTO {
        $groupLevel = new GroupLevel(
            new GroupLevelId(),
            $command->name,
            $command->description,
            $command->sortOrder,
            new DateTimeValue('now'),
            null,
        );

        $this->repository->save($groupLevel);

        $dto = GroupLevelDTO::fromEntity($groupLevel);

        return $dto;
    }
}
