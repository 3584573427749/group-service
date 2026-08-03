<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Domain\ValueObjects\UserId;

class DeleteLeaderGroupsHandler extends GroupLeaderHandler {
    public function handle(UserId $id) : void {
        $this->repository->deleteByUser($id);
    }
}
