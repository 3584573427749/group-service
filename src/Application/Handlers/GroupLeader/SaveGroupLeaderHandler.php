<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Application\Commands\GroupLeader\GroupLeaderCommand;
use App\Domain\Entities\GroupLeader;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\Repositories\GroupRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;
use Doctrine\DBAL\Connection;

class SaveGroupLeaderHandler extends GroupLeaderHandler {
    public function __construct(Connection $db, GroupLeaderRepository $repository, private GroupRepository $groupRepository, private UserRepository $userRepository) {
        parent::__construct($db, $repository);
    }

    public function handle(GroupLeaderCommand $command) : void {
        // Kontrollera att grupp och ledare finns redan
        $this->groupRepository->getById(new GroupId($command->groupId));
        $this->userRepository->getById(new UserId($command->userId));

        $groupLeader = $this->repository->get(new GroupId($command->groupId), new UserId($command->userId));

        if ($groupLeader === false) {
            $groupLeader = GroupLeader::fromCommand($command);
        }

        $this->repository->save($groupLeader);

    }
}
