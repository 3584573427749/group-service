<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Domain\ValueObjects\GroupId;

class DeleteGroupLeadersHandler extends GroupLeaderHandler {
    public function handle(GroupId $id) : void {
        $this->repository->deleteByGroup($id);
    }
}
