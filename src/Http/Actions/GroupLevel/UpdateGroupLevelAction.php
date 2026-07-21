<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLevel;

use App\Application\Commands\GroupLevel\UpdateGroupLevelCommand;
use App\Application\Handlers\GroupLevel\UpdateGroupLevelHandler;
use App\Application\Validators\UpdateGroupLevelValidator;
use App\Domain\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class UpdateGroupLevelAction extends GroupLevelAction {
    public function __construct(LoggerInterface $logger, private UpdateGroupLevelHandler $handler) {
        parent::__construct($logger);
    }

    protected function action() : Response {
        $id = $this->request->getAttribute('id');
        $data = (array)$this->request->getParsedBody();
        $data['groupLevelId'] = $id;

        $errors = UpdateGroupLevelValidator::validate($data);
        if (count($errors) > 0) {
            throw new ValidationException('Felaktig indata', $errors);
        }

        $groupLevelCommand = UpdateGroupLevelCommand::fromRequest($data);
        $dto = $this->handler->handle($groupLevelCommand);

        return $this->respondWithData($dto);

    }
}
