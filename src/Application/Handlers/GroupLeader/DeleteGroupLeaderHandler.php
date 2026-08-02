<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;

class DeleteGroupLeaderHandler extends GroupLeaderHandler {
    public function handle(GroupId $groupId, UserId $userId) : void {
        $this->repository->delete($groupId, $userId);
    }
}
