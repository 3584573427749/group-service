<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\Group;

use App\Application\Handlers\Group\GetGroupHandler;
use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\Entities\Group;
use App\Domain\Enums\Venue;
use App\Domain\Repositories\GroupRepository;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;
use PHPUnit\Framework\TestCase;

final class GetGroupHandlerTest extends TestCase {
    public function testGetAllReturnsEmptyArray() : void {
        $repository = $this->createMock(GroupRepository::class);

        $repository
            ->expects(self::once())
            ->method('getAll')
            ->willReturn([]);

        $handler = new class($repository) extends GetGroupHandler {
            public function __construct(GroupRepository $repository) {
                $this->repository = $repository;
            }
        };

        $result = $handler->getAll();

        self::assertSame([], $result);
    }

    public function testGetAllReturnsDtos() : void {
        $repository = $this->createMock(GroupRepository::class);

        $repository
            ->expects(self::once())
            ->method('getAll')
            ->willReturn([
                $this->createGroup(
                    '550e8400-e29b-41d4-a716-446655440000',
                    '12345678-9012-3456-7890-123456789012',
                    'Baddaren',
                    'För nybörjare',
                    Venue::ALANDS_IDROTTCENTER,
                    1,
                    1,
                ),
                $this->createGroup(
                    '660e8400-e29b-41d4-a716-446655440000',
                    '12345678-9012-3456-7890-123456789012',
                    'Pingvinen',
                    'För avancerade',
                    Venue::MARIEBAD,
                    1,
                    1,
                ),
            ]);

        $handler = new class($repository) extends GetGroupHandler {
            public function __construct(GroupRepository $repository) {
                $this->repository = $repository;
            }
        };

        $result = $handler->getAll();

        self::assertCount(2, $result);

        self::assertInstanceOf(GroupDTO::class, $result[0]);
        self::assertInstanceOf(GroupDTO::class, $result[1]);

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
        $repository = $this->createMock(GroupRepository::class);

        $id = new GroupId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $repository
            ->expects(self::once())
            ->method('getById')
            ->with($id)
            ->willReturn(
                $this->createGroup(
                    '550e8400-e29b-41d4-a716-446655440000',
                    '12345678-9012-3456-7890-123456789012',
                    'Baddaren',
                    'För nybörjare',
                    Venue::ALANDS_IDROTTCENTER,
                    1,
                    1,
                ),
            );

        $handler = new class($repository) extends GetGroupHandler {
            public function __construct(GroupRepository $repository) {
                $this->repository = $repository;
            }
        };

        $result = $handler->getId($id);

        self::assertInstanceOf(GroupDTO::class, $result);

        $json = $result->jsonSerialize();

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $json['id']);
        self::assertSame('Baddaren', $json['name']);
        self::assertSame(Venue::ALANDS_IDROTTCENTER->value, $json['venue']);
        self::assertSame(1, $json['active']);
        self::assertSame(1, $json['competitive']);
    }

    public function testGetIdPropagatesRepositoryException() : void {
        $repository = $this->createMock(GroupRepository::class);

        $id = new GroupId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $repository
            ->expects(self::once())
            ->method('getById')
            ->with($id)
            ->willThrowException(
                new \InvalidArgumentException('Group saknas'),
            );

        $handler = new class($repository) extends GetGroupHandler {
            public function __construct(GroupRepository $repository) {
                $this->repository = $repository;
            }
        };

        self::expectException(\InvalidArgumentException::class);

        $handler->getId($id);
    }

    private function createGroup(
        string $id,
        string $groupLevelId,
        string $name,
        string $description,
        Venue $venue,
        int $active,
        int $competitive,
    ) : Group {
        return new Group(
            new GroupId($id),
            new GroupLevelId($groupLevelId),
            $name,
            $description,
            $venue,
            $active,
            $competitive,
            new DateTimeValue('2026-01-01 10:00:00'),
            null,
        );
    }
}
