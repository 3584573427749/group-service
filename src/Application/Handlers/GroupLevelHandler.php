<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Domain\Repositories\GroupLevelRepository;
use Doctrine\DBAL\Connection;

abstract class GroupLevelHandler {
    public function __construct(protected Connection $db, protected GroupLevelRepository $repository) {

    }

}
