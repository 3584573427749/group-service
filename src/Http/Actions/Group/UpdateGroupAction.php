<?php

declare(strict_types=1);

namespace App\Http\Actions\Group;

use App\Application\Commands\Group\UpdateGroupCommand;
use App\Application\Handlers\Group\UpdateGroupHandler;
use App\Application\Validators\GroupValidator;
use App\Domain\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class UpdateGroupAction extends GroupAction {
    public function __construct(LoggerInterface $logger, private UpdateGroupHandler $handler) {
        parent::__construct($logger);
    }

    protected function action() : Response {
        $id = $this->request->getAttribute('id');
        $data = (array)$this->request->getParsedBody();
        $data['groupId'] = $id;

        $errors = GroupValidator::validateUpdate($data);
        if (count($errors) > 0) {
            throw new ValidationException('Felaktig indata', $errors);
        }

        $groupCommand = UpdateGroupCommand::fromRequest($data);
        $dto = $this->handler->handle($groupCommand);

        return $this->respondWithData($dto);

    }
}
