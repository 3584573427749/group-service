<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Domain\ValueObjects\GroupLevelId;

class DeleteGroupLevelHandler extends GroupLevelHandler {
    public function handle(GroupLevelId $id) : void {

        $this->repository->delete($id);
    }
}
