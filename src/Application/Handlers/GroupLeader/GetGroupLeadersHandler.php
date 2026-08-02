<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Domain\DataTransportObjects\UserDTO;
use App\Domain\ValueObjects\GroupId;

class GetGroupLeadersHandler extends GroupLeaderHandler {
    /**
     * @return array<UserDTO>
     */
    public function handle(GroupId $id) : array {
        $users = $this->repository->getUsers($id);

        return array_map(
            fn ($user) => UserDTO::fromEntity($user),
            $users,
        );
    }
}
