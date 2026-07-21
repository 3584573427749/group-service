<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLevel;

use App\Application\Handlers\GroupLevel\GetGroupLevelHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class GetAllGroupLevelsAction extends GroupLevelAction {
    public function __construct(LoggerInterface $logger, private GetGroupLevelHandler $handler) {
        parent::__construct($logger);
    }

    protected function action() : Response {
        $groupLevelDTOs = $this->handler->getAll();

        return $this->respondWithData($groupLevelDTOs);

    }
}
