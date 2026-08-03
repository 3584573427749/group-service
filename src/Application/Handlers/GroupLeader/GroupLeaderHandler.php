<?php

declare(strict_types=1);

namespace App\Application\Handlers\GroupLeader;

use App\Domain\Repositories\GroupLeaderRepository;
use Doctrine\DBAL\Connection;

abstract class GroupLeaderHandler {
    public function __construct(protected Connection $db, protected GroupLeaderRepository $repository) {

    }

}
