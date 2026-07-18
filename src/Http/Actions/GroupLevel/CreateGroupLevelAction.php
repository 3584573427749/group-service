<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLevel;

use App\Application\Commands\CreateGroupLevelCommand;
use App\Application\Handlers\CreateGroupLevelHandler;
use App\Application\Validators\CreateGroupLevelValidator;
use App\Domain\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateGroupLevelAction extends GroupLevelAction {
    public function __construct(LoggerInterface $logger, private CreateGroupLevelHandler $handler) {
        parent::__construct($logger);
    }

    /**
     * @inheritDoc
     */
    protected function action() : Response {
        $data = (array)$this->request->getParsedBody();

        $errors = CreateGroupLevelValidator::validate($data);
        if (count($errors) > 0) {
            throw new ValidationException('Felaktig indata', $errors);
        }

        $groupLevelCommand = CreateGroupLevelCommand::fromRequest($data);
        $dto = $this->handler->handle($groupLevelCommand);

        return $this->respondWithData($dto, 201);
    }
}
