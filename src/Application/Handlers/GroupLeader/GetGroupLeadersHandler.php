<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Domain\DataTransportObjects\UserDTO;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\Repositories\GroupRepository;
use App\Domain\ValueObjects\GroupId;
use Doctrine\DBAL\Connection;

class GetGroupLeadersHandler extends GroupLeaderHandler {
    public function __construct(Connection $db, GroupLeaderRepository $repository, private GroupRepository $groupRepository) {
        parent::__construct($db, $repository);
    }

    /**
     * @return array<UserDTO>
     */
    public function handle(GroupId $id) : array {
        $group = $this->groupRepository->getById($id);

        if ($group->getActive() === 0) {
            throw new NotFoundException('Gruppen är inaktiv');
        }

        $users = $this->repository->getUsers($id);

        return array_map(
            fn ($user) => UserDTO::fromEntity($user),
            $users,
        );
    }
}
