<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLevel;

use App\Application\Handlers\GroupLevel\DeleteGroupLevelHandler;
use App\Domain\Repositories\GroupLevelRepository;
use App\Domain\ValueObjects\GroupLevelId;
use PHPUnit\Framework\TestCase;

final class DeleteGroupLevelHandlerTest extends TestCase {
    public function testHandleDeletesGroupLevel() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $id = new GroupLevelId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $repository
            ->expects(self::once())
            ->method('delete')
            ->with($id);

        $handler = new class($repository) extends DeleteGroupLevelHandler {
            public function __construct(GroupLevelRepository $repository) {
                $this->repository = $repository;
            }
        };

        $handler->handle($id);
    }

    public function testHandlePropagatesRepositoryException() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $id = new GroupLevelId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $repository
            ->expects(self::once())
            ->method('delete')
            ->with($id)
            ->willThrowException(
                new \RuntimeException('Database error'),
            );

        $handler = new class($repository) extends DeleteGroupLevelHandler {
            public function __construct(GroupLevelRepository $repository) {
                $this->repository = $repository;
            }
        };

        self::expectException(\RuntimeException::class);

        $handler->handle($id);
    }
}
