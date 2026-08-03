<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLeader;

use App\Application\Handlers\GroupLeader\GetLeaderGroupsHandler;
use App\Domain\ValueObjects\UserId;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class GetLeaderGroupsAction extends GroupLeaderAction {
    public function __construct(LoggerInterface $logger, private GetLeaderGroupsHandler $handler) {
        parent::__construct($logger);
    }

    /**
     * @inheritDoc
     */
    protected function action() : Response {
        $id = $this->request->getAttribute('id');

        $userId = new UserId($id);

        $dto = $this->handler->handle($userId);

        return $this->respondWithData($dto);

    }
}
