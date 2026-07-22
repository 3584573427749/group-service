<?php

declare(strict_types=1);

namespace App\Http\Actions\Group;

use App\Http\Actions\Action;
use Psr\Log\LoggerInterface;

abstract class GroupAction extends Action {
    public function __construct(LoggerInterface $logger) {
        parent::__construct($logger);
    }
}
