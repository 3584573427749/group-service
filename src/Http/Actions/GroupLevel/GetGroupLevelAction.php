<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLevel;

use App\Application\Handlers\GroupLevel\GetGroupLevelHandler;
use App\Domain\ValueObjects\GroupLevelId;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class GetGroupLevelAction extends GroupLevelAction {
    public function __construct(LoggerInterface $logger, private GetGroupLevelHandler $handler) {
        parent::__construct($logger);
    }

    protected function action() : Response {
        $id = $this->request->getAttribute('id');

        $groupLevelId = new GroupLevelId($id);
        $groupLevelDTO = $this->handler->getId($groupLevelId);

        return $this->respondWithData($groupLevelDTO);
    }
}
