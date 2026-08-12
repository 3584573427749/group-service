<?php

declare(strict_types=1);

namespace App\Http\Actions\GroupLevel;

use App\Application\Commands\GroupLevel\UpdateGroupLevelSortOrderCommand;
use App\Application\Handlers\GroupLevel\UpdateGroupLevelSortOrderHandler;
use App\Application\Validators\UpdateGroupLevelSortOrderValidator;
use App\Domain\Exception\ValidationException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;

class UpdateGroupLevelSortOrderAction extends GroupLevelAction {
    public function __construct(LoggerInterface $logger, private UpdateGroupLevelSortOrderHandler $handler) {
        parent::__construct($logger);
    }

    protected function action() : Response {
        $data = (array)$this->request->getParsedBody();

        $errors = UpdateGroupLevelSortOrderValidator::validate($data);
        if (count($errors) > 0) {
            throw new ValidationException('Felaktig indata', $errors);
        }
        $groupLevelCommand = UpdateGroupLevelSortOrderCommand::fromRequest($data);
        $this->handler->handle($groupLevelCommand);

        return $this->response->withStatus(204);

    }
}
