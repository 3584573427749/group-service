<?php

declare(strict_types=1);

namespace App\Http\Actions\User;

use App\Application\Handlers\User\GetUserHandler;
use App\Http\Actions\Group\GroupAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class GetAllUsersAction extends GroupAction {
    public function __construct(LoggerInterface $logger, private GetUserHandler $handler) {
        parent::__construct($logger);
    }

    protected function action() : Response {
        $DTOs = $this->handler->getAll();

        return $this->respondWithData($DTOs);

    }
}
