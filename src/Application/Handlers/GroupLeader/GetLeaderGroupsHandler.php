<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Domain\DataTransportObjects\GroupLeaderDTO;
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
     * @return array<GroupLeaderDTO>
     */
    public function handle(UserId $id) : array {
        $user = $this->userRepository->getById($id);
        if (!$user) {
            throw new NotFoundException('Användaren finns inte');
        }


        return $this->repository->getLeaderGroups($id);

    }
}
