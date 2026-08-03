<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLeader;

use App\Application\Handlers\GroupLeader\DeleteGroupLeadersHandler;
use App\Domain\ValueObjects\GroupId;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DeleteGroupLeadersAction extends GroupLeaderAction {
    public function __construct(LoggerInterface $logger, private DeleteGroupLeadersHandler $handler) {
        parent::__construct($logger);
    }

    /**
     * @inheritDoc
     */
    protected function action() : Response {
        $group_id = $this->request->getAttribute('id');

        $groupId = new GroupId($group_id);
        $this->handler->handle($groupId);

        return $this->response->withStatus(204);
    }
}
