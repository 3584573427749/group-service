<?php

declare(strict_types=1);

namespace App\Application\Handlers\Group;

use App\Application\Commands\Group\UpdateGroupCommand;
use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\ValueObjects\DateTimeValue;

class UpdateGroupHandler extends GroupHandler {
    public function handle(UpdateGroupCommand $command) : GroupDTO {

        $group = $this->repository->getById($command->id);

        $group->setName($command->name);
        $group->setDescription($command->description);
        $group->setVenue($command->venue);
        $group->setActive($command->active);
        $group->setCompetitive($command->competitive);
        $group->setUpdatedAt(new DateTimeValue('now'));

        $this->repository->save($group);

        $dto = GroupDTO::fromEntity($group);

        return $dto;
    }
}
