<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Application\Commands\GroupLeader\GroupLeaderCommand;
use App\Domain\Entities\GroupLeader;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;

class SaveGroupLeaderHandler extends GroupLeaderHandler {
    public function handle(GroupLeaderCommand $command) : void {
        $groupLeader = $this->repository->get(new GroupId($command->groupId), new UserId($command->userId));

        if ($groupLeader === false) {
            $groupLeader = GroupLeader::fromCommand($command);
        }

        $this->repository->save($groupLeader);

    }
}
