<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLevel;

use App\Application\Commands\GroupLevel\UpdateGroupLevelSortOrderCommand;

class UpdateGroupLevelSortOrderHandler extends GroupLevelHandler {
    public function handle(UpdateGroupLevelSortOrderCommand $command) : void {

        $this->repository->updateOrder($command->command);
    }
}
