<?php

declare(strict_types=1);

namespace App\Http\Actions\Group;

use App\Application\Handlers\Group\GetGroupHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class GetAllGroupsAction extends GroupAction {
    public function __construct(LoggerInterface $logger, private GetGroupHandler $handler) {
        parent::__construct($logger);
    }

    protected function action() : Response {
        $groupDTOs = $this->handler->getAll();

        return $this->respondWithData($groupDTOs);

    }
}
