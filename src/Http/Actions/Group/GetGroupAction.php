<?php

declare(strict_types=1);

namespace App\Http\Actions\Group;

use App\Application\Handlers\Group\GetGroupHandler;
use App\Domain\ValueObjects\GroupId;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class GetGroupAction extends GroupAction {
    public function __construct(LoggerInterface $logger, private GetGroupHandler $handler) {
        parent::__construct($logger);
    }

    protected function action() : Response {
        $id = $this->request->getAttribute('id');

        $groupId = new GroupId($id);
        $groupDTO = $this->handler->getId($groupId);

        return $this->respondWithData($groupDTO);
    }
}
