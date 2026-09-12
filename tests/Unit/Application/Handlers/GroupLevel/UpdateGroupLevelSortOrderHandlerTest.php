<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLevel;

use App\Application\Commands\GroupLevel\UpdateGroupLevelSortOrderCommand;
use App\Application\Handlers\GroupLevel\UpdateGroupLevelSortOrderHandler;
use App\Domain\Repositories\GroupLevelRepository;
use PHPUnit\Framework\TestCase;

final class UpdateGroupLevelSortOrderHandlerTest extends TestCase {
    public function testHandleUpdatesSortOrder() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $command = UpdateGroupLevelSortOrderCommand::fromRequest([
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => 1,
            ],
            [
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => 2,
            ],
        ]);

        $repository
            ->expects(self::once())
            ->method('updateOrder')
            ->with($command->command);

        $handler = new class($repository) extends UpdateGroupLevelSortOrderHandler {
            public function __construct(
                GroupLevelRepository $repository,
            ) {
                $this->repository = $repository;
            }
        };

        $handler->handle($command);
    }
}
