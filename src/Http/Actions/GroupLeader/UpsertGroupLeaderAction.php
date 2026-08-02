<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLeader;

use App\Application\Commands\GroupLeader\GroupLeaderCommand;
use App\Application\Handlers\GroupLeader\SaveGroupLeaderHandler;
use App\Application\Validators\GroupLeaderValidator;
use App\Domain\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class UpsertGroupLeaderAction extends GroupLeaderAction {
    public function __construct(LoggerInterface $logger, private SaveGroupLeaderHandler $handler) {
        parent::__construct($logger);
    }

    /**
     * @inheritDoc
     */
    protected function action() : Response {
        $data = (array)$this->request->getParsedBody();

        $errors = GroupLeaderValidator::validate($data);
        if (count($errors) > 0) {
            throw new ValidationException('Felaktig indata', $errors);
        }

        $groupLeaderCommand = GroupLeaderCommand::fromRequest($data);
        $this->handler->handle($groupLeaderCommand);

        return $this->response->withStatus(204);
    }
}
