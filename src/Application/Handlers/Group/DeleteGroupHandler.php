<?php

declare(strict_types=1);

namespace App\Application\Handlers\Group;

use App\Domain\ValueObjects\GroupId;

class DeleteGroupHandler extends GroupHandler {
    public function handle(GroupId $id) : void {

        $this->repository->delete($id);
    }
}
