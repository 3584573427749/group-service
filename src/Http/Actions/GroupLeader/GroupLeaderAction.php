<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLeader;

use App\Http\Actions\Action;
use Psr\Log\LoggerInterface;

abstract class GroupLeaderAction extends Action {
    public function __construct(LoggerInterface $logger) {
        parent::__construct($logger);
    }
}
