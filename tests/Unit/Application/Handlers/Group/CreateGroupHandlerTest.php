<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\Group;

use App\Application\Commands\Group\CreateGroupCommand;
use App\Application\Handlers\Group\CreateGroupHandler;
use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\Repositories\GroupRepository;
use PHPUnit\Framework\TestCase;

final class CreateGroupHandlerTest extends TestCase {
    public function testHandleCreatesGroupSuccessfully() : void {
        $repository = $this->createMock(GroupRepository::class);

        $command = CreateGroupCommand::fromRequest([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '12345678-9012-3456-7890-123456789012',
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 1,
        ]);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(
                self::callback(function ($group) {
                    return $group->getName() === 'Baddaren'
                        && $group->getDescription() === 'För nybörjare'
                        && $group->getActive() === 1
                        && $group->getCompetitive() === 1;
                }),
            );

        $handler = new class($repository) extends CreateGroupHandler {
            public function __construct(GroupRepository $repository) {
                $this->repository = $repository;
            }
        };

        $result = $handler->handle($command);

        self::assertInstanceOf(GroupDTO::class, $result);

        $json = $result->jsonSerialize();

        self::assertSame('Baddaren', $json['name']);
        self::assertSame('För nybörjare', $json['description']);
        self::assertSame(1, $json['active']);
        self::assertSame(1, $json['competitive']);
    }

    public function testHandlePropagatesRepositoryException() : void {
        $repository = $this->createMock(GroupRepository::class);

        $command = CreateGroupCommand::fromRequest([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '12345678-9012-3456-7890-123456789012',
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 1,
        ]);

        $repository
            ->expects(self::once())
            ->method('save')
            ->willThrowException(
                new \RuntimeException('Database error'),
            );

        $handler = new class($repository) extends CreateGroupHandler {
            public function __construct(GroupRepository $repository) {
                $this->repository = $repository;
            }
        };

        self::expectException(\RuntimeException::class);

        $handler->handle($command);
    }
}
