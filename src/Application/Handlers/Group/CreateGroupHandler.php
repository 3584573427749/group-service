<?php

declare(strict_types=1);

namespace App\Application\Handlers\Group;

use App\Application\Commands\Group\CreateGroupCommand;
use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\Entities\Group;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;

class CreateGroupHandler extends GroupHandler {
    public function handle(CreateGroupCommand $command) : GroupDTO {
        $group = new Group(
            new GroupId(),
            $command->groupLevelId,
            $command->name,
            $command->description,
            $command->venue,
            $command->active,
            $command->competitive,
            new DateTimeValue('now'),
            null,
        );

        $this->repository->save($group);

        $dto = GroupDTO::fromEntity($group);

        return $dto;
    }
}
