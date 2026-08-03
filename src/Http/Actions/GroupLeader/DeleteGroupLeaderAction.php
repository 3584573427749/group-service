<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLeader;

use App\Application\Handlers\GroupLeader\DeleteGroupLeaderHandler;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DeleteGroupLeaderAction extends GroupLeaderAction {
    public function __construct(LoggerInterface $logger, private DeleteGroupLeaderHandler $handler) {
        parent::__construct($logger);
    }

    /**
     * @inheritDoc
     */
    protected function action() : Response {
        $group_id = $this->request->getAttribute('id');
        $user_id = $this->request->getAttribute('userId');

        $groupId = new GroupId($group_id);
        $userId = new UserId($user_id);
        $this->handler->handle($groupId, $userId);

        return $this->response->withStatus(204);
    }
}
