<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\Exception\NotFoundException;
use App\Domain\Repositories\GroupLeaderRepository;
use App\Domain\Repositories\UserRepository;
use App\Domain\ValueObjects\UserId;
use Doctrine\DBAL\Connection;

class GetLeaderGroupsHandler extends GroupLeaderHandler {
    public function __construct(Connection $db, GroupLeaderRepository $repository, private UserRepository $userRepository) {
        parent::__construct($db, $repository);
    }

    /**
     * @return array<GroupDTO>
     */
    public function handle(UserId $id) : array {
        $user = $this->userRepository->getById($id);
        if (!$user) {
            throw new NotFoundException('Användaren finns inte');
        }


        $groups = $this->repository->getGroups($id);

        return array_map(
            fn ($group) => GroupDTO::fromEntity($group),
            $groups,
        );
    }
}
