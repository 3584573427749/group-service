<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\GroupLevel;

use App\Application\Handlers\GroupLevel\GetGroupLevelHandler;
use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\Entities\GroupLevel;
use App\Domain\Repositories\GroupLevelRepository;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;
use PHPUnit\Framework\TestCase;

final class GetGroupLevelHandlerTest extends TestCase {
    public function testGetAllReturnsEmptyArray() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $repository
            ->expects(self::once())
            ->method('getAll')
            ->willReturn([]);

        $handler = new class($repository) extends GetGroupLevelHandler {
            public function __construct(GroupLevelRepository $repository) {
                $this->repository = $repository;
            }
        };

        $result = $handler->getAll();

        self::assertSame([], $result);
    }

    public function testGetAllReturnsDtos() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $repository
            ->expects(self::once())
            ->method('getAll')
            ->willReturn([
                $this->createGroupLevel(
                    '550e8400-e29b-41d4-a716-446655440000',
                    'Baddaren',
                    1,
                ),
                $this->createGroupLevel(
                    '660e8400-e29b-41d4-a716-446655440000',
                    'Pingvinen',
                    2,
                ),
            ]);

        $handler = new class($repository) extends GetGroupLevelHandler {
            public function __construct(GroupLevelRepository $repository) {
                $this->repository = $repository;
            }
        };

        $result = $handler->getAll();

        self::assertCount(2, $result);

        self::assertInstanceOf(GroupLevelDTO::class, $result[0]);
        self::assertInstanceOf(GroupLevelDTO::class, $result[1]);

        self::assertSame(
            'Baddaren',
            $result[0]->jsonSerialize()['name'],
        );

        self::assertSame(
            'Pingvinen',
            $result[1]->jsonSerialize()['name'],
        );
    }

    public function testGetIdReturnsDto() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $id = new GroupLevelId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $repository
            ->expects(self::once())
            ->method('getById')
            ->with($id)
            ->willReturn(
                $this->createGroupLevel(
                    '550e8400-e29b-41d4-a716-446655440000',
                    'Baddaren',
                    1,
                ),
            );

        $handler = new class($repository) extends GetGroupLevelHandler {
            public function __construct(GroupLevelRepository $repository) {
                $this->repository = $repository;
            }
        };

        $result = $handler->getId($id);

        self::assertInstanceOf(GroupLevelDTO::class, $result);

        $json = $result->jsonSerialize();

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $json['id'],
        );
        self::assertSame('Baddaren', $json['name']);
        self::assertSame(1, $json['sortOrder']);
    }

    public function testGetIdPropagatesRepositoryException() : void {
        $repository = $this->createMock(GroupLevelRepository::class);

        $id = new GroupLevelId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $repository
            ->expects(self::once())
            ->method('getById')
            ->with($id)
            ->willThrowException(
                new \InvalidArgumentException('GroupLevel saknas'),
            );

        $handler = new class($repository) extends GetGroupLevelHandler {
            public function __construct(GroupLevelRepository $repository) {
                $this->repository = $repository;
            }
        };

        self::expectException(\InvalidArgumentException::class);

        $handler->getId($id);
    }

    private function createGroupLevel(
        string $id,
        string $name,
        int $sortOrder,
    ) : GroupLevel {
        return new GroupLevel(
            new GroupLevelId($id),
            $name,
            'Beskrivning',
            $sortOrder,
            new DateTimeValue('2026-01-01 10:00:00'),
            null,
        );
    }
}
