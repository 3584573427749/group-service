<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLevel;

use App\Application\Handlers\GroupLevel\DeleteGroupLevelHandler;
use App\Domain\ValueObjects\GroupLevelId;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class DeleteGroupLevelAction extends GroupLevelAction {
    public function __construct(LoggerInterface $logger, private DeleteGroupLevelHandler $handler) {
        parent::__construct($logger);
    }

    protected function action() : Response {
        $id = $this->request->getAttribute('id');

        $groupLevelId = new GroupLevelId($id);
        $this->handler->handle($groupLevelId);

        return $this->response->withStatus(204);
    }
}
