<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\Group;

use App\Application\Handlers\Group\DeleteGroupHandler;
use App\Domain\Repositories\GroupRepository;
use App\Domain\ValueObjects\GroupId;
use PHPUnit\Framework\TestCase;

final class DeleteGroupHandlerTest extends TestCase {
    public function testHandleDeletesGroup() : void {
        $repository = $this->createMock(GroupRepository::class);

        $id = new GroupId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $repository
            ->expects(self::once())
            ->method('delete')
            ->with($id);

        $handler = new class($repository) extends DeleteGroupHandler {
            public function __construct(GroupRepository $repository) {
                $this->repository = $repository;
            }
        };

        $handler->handle($id);
    }

    public function testHandlePropagatesRepositoryException() : void {
        $repository = $this->createMock(GroupRepository::class);

        $id = new GroupId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $repository
            ->expects(self::once())
            ->method('delete')
            ->with($id)
            ->willThrowException(
                new \RuntimeException('Database error'),
            );

        $handler = new class($repository) extends DeleteGroupHandler {
            public function __construct(GroupRepository $repository) {
                $this->repository = $repository;
            }
        };

        self::expectException(\RuntimeException::class);

        $handler->handle($id);
    }
}
