<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLevel;

use App\Application\Commands\GroupLevel\UpdateGroupLevelCommand;
use App\Application\Handlers\GroupLevel\UpdateGroupLevelHandler;
use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\Entities\GroupLevel;
use App\Domain\Repositories\GroupLevelRepository;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class UpdateGroupLevelHandlerTest extends TestCase {
    public function testHandleUpdatesGroupLevel() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $groupLevel = $this->createGroupLevel();

        $command = UpdateGroupLevelCommand::fromRequest([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Pingvinen',
            'description' => 'Kan simma själv',
            'sortOrder' => 2,
        ]);

        $repository
            ->expects(self::once())
            ->method('getById')
            ->with($command->id)
            ->willReturn($groupLevel);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(
                self::callback(function (GroupLevel $groupLevel) {
                    return $groupLevel->getName() === 'Pingvinen'
                        && $groupLevel->getDescription() === 'Kan simma själv'
                        && $groupLevel->getSortOrder() === 2
                        && $groupLevel->getUpdatedAt() !== null;
                }),
            );

        $handler = new class($repository) extends UpdateGroupLevelHandler {
            public function __construct(GroupLevelRepository $repository) {
                $this->repository = $repository;
            }
        };

        $result = $handler->handle($command);

        self::assertInstanceOf(GroupLevelDTO::class, $result);

        $json = $result->jsonSerialize();

        self::assertSame('Pingvinen', $json['name']);
        self::assertSame('Kan simma själv', $json['description']);
        self::assertSame(2, $json['sortOrder']);
        self::assertNotNull($json['updatedAt']);
    }

    public function testHandlePropagatesExceptionFromRepository() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $command = UpdateGroupLevelCommand::fromRequest([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Pingvinen',
            'description' => 'Kan simma själv',
            'sortOrder' => 2,
        ]);

        $repository
            ->expects(self::once())
            ->method('getById')
            ->with($command->id)
            ->willThrowException(
                new InvalidArgumentException('GroupLevel saknas'),
            );

        $handler = new class($repository) extends UpdateGroupLevelHandler {
            public function __construct(GroupLevelRepository $repository) {
                $this->repository = $repository;
            }
        };

        self::expectException(InvalidArgumentException::class);

        $handler->handle($command);
    }

    private function createGroupLevel() : GroupLevel {
        return new GroupLevel(
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            1,
            new DateTimeValue('2026-01-01 10:00:00'),
            null,
        );
    }
}
