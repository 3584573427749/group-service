<?php

declare(strict_types=1);

namespace App\Http\Actions\Group;

use App\Application\Commands\Group\CreateGroupCommand;
use App\Application\Handlers\Group\CreateGroupHandler;
use App\Application\Validators\GroupValidator;
use App\Domain\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class CreateGroupAction extends GroupAction {
    public function __construct(LoggerInterface $logger, private CreateGroupHandler $handler) {
        parent::__construct($logger);
    }

    /**
     * @inheritDoc
     */
    protected function action() : Response {
        $data = (array)$this->request->getParsedBody();

        $errors = GroupValidator::validateCreate($data);
        if (count($errors) > 0) {
            throw new ValidationException('Felaktig indata', $errors);
        }

        $groupCommand = CreateGroupCommand::fromRequest($data);
        $dto = $this->handler->handle($groupCommand);

        return $this->respondWithData($dto, 201);
    }
}
