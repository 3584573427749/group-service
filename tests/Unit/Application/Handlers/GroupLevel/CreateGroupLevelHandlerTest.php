<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLevel;

use App\Application\Commands\GroupLevel\CreateGroupLevelCommand;
use App\Application\Handlers\GroupLevel\CreateGroupLevelHandler;
use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\Repositories\GroupLevelRepository;
use PHPUnit\Framework\TestCase;

final class CreateGroupLevelHandlerTest extends TestCase {
    public function testHandleCreatesGroupLevelSuccessfully() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $command = CreateGroupLevelCommand::fromRequest([
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'sortOrder' => 1,
        ]);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(
                self::callback(function ($groupLevel) {
                    return $groupLevel->getName() === 'Baddaren'
                        && $groupLevel->getDescription() === 'För nybörjare'
                        && $groupLevel->getSortOrder() === 1;
                }),
            );

        $handler = new class($repository) extends CreateGroupLevelHandler {
            public function __construct(GroupLevelRepository $repository) {
                $this->repository = $repository;
            }
        };

        $result = $handler->handle($command);

        self::assertInstanceOf(GroupLevelDTO::class, $result);

        $json = $result->jsonSerialize();

        self::assertSame('Baddaren', $json['name']);
        self::assertSame('För nybörjare', $json['description']);
        self::assertSame(1, $json['sortOrder']);
    }

    public function testHandlePropagatesRepositoryException() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $command = CreateGroupLevelCommand::fromRequest([
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'sortOrder' => 1,
        ]);

        $repository
            ->expects(self::once())
            ->method('save')
            ->willThrowException(
                new \RuntimeException('Database error'),
            );

        $handler = new class($repository) extends CreateGroupLevelHandler {
            public function __construct(GroupLevelRepository $repository) {
                $this->repository = $repository;
            }
        };

        self::expectException(\RuntimeException::class);

        $handler->handle($command);
    }
}
