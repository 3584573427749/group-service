<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\ValueObjects\UserId;

class GetLeaderGroupsHandler extends GroupLeaderHandler {
    /**
     * @return array<GroupDTO>
     */
    public function handle(UserId $id) : array {
        $groups = $this->repository->getGroups($id);

        return array_map(
            fn ($group) => GroupDTO::fromEntity($group),
            $groups,
        );
    }
}
