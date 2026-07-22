<?php

declare(strict_types=1);

namespace App\Application\Handlers\Group;

use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\ValueObjects\GroupId;

class GetGroupHandler extends GroupHandler {
    /**
     * @return array<GroupDTO>
     */
    public function getAll() : array {

        return array_map(
            fn ($group) => GroupDTO::fromEntity($group),
            $this->repository->getAll(),
        );
    }

    public function getId(GroupId $id) : GroupDTO {
        $group = $this->repository->getById($id);

        return GroupDTO::fromEntity($group);
    }
}
