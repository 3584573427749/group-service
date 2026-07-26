<?php

declare(strict_types=1);

namespace App\Application\Handlers\Group;

use App\Domain\Repositories\GroupRepository;
use Doctrine\DBAL\Connection;

abstract class GroupHandler {
    public function __construct(protected Connection $db, protected GroupRepository $repository) {

    }

}
