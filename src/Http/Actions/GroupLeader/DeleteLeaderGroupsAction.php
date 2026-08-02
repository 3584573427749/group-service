<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLeader;

use App\Application\Handlers\GroupLeader\DeleteLeaderGroupsHandler;
use App\Domain\ValueObjects\UserId;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DeleteLeaderGroupsAction extends GroupLeaderAction {
    public function __construct(LoggerInterface $logger, private DeleteLeaderGroupsHandler $handler) {
        parent::__construct($logger);
    }

    /**
     * @inheritDoc
     */
    protected function action() : Response {
        $user_id = $this->request->getAttribute('id');

        $userId = new UserId($user_id);
        $this->handler->handle($userId);

        return $this->response->withStatus(204);
    }
}
