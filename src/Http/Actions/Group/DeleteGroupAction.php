<?php

declare(strict_types=1);

namespace App\Http\Actions\Group;

use App\Application\Handlers\Group\DeleteGroupHandler;
use App\Domain\ValueObjects\GroupId;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DeleteGroupAction extends GroupAction {
    public function __construct(LoggerInterface $logger, private DeleteGroupHandler $handler) {
        parent::__construct($logger);
    }

    protected function action() : Response {
        $id = $this->request->getAttribute('id');

        $groupId = new GroupId($id);
        $this->handler->handle($groupId);

        return $this->response->withStatus(204);
    }
}
