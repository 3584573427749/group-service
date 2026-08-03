<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLeader;

use App\Application\Handlers\GroupLeader\GetGroupLeadersHandler;
use App\Domain\ValueObjects\GroupId;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class GetGroupLeadersAction extends GroupLeaderAction {
    public function __construct(LoggerInterface $logger, private GetGroupLeadersHandler $handler) {
        parent::__construct($logger);
    }

    /**
     * @inheritDoc
     */
    protected function action() : Response {
        $id = $this->request->getAttribute('id');

        $groupId = new GroupId($id);

        $dto = $this->handler->handle($groupId);

        return $this->respondWithData($dto);

    }
}
