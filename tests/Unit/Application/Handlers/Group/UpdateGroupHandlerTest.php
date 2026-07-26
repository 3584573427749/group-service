<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers\Group;

use App\Application\Commands\Group\UpdateGroupCommand;
use App\Application\Handlers\Group\UpdateGroupHandler;
use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\Entities\Group;
use App\Domain\Enums\Venue;
use App\Domain\Repositories\GroupRepository;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class UpdateGroupHandlerTest extends TestCase {
    public function testHandleUpdatesGroup() : void {
        $repository = $this->createMock(GroupRepository::class);

        $group = $this->createGroup();

        $command = UpdateGroupCommand::fromRequest([
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
            ->method('getById')
            ->with($command->id)
            ->willReturn($group);

        $repository
            ->expects(self::once())
            ->method('save')
            ->with(
                self::callback(function (Group $group) {
                    return $group->getName() === 'Baddaren'
                        && $group->getDescription() === 'För nybörjare'
                        && $group->getVenue()->value === 'Mariebad'
                        && $group->getActive() === 1
                        && $group->getCompetitive() === 1
                        && $group->getUpdatedAt() !== null;
                }),
            );

        $handler = new class($repository) extends UpdateGroupHandler {
            public function __construct(GroupRepository $repository) {
                $this->repository = $repository;
            }
        };

        $result = $handler->handle($command);

        self::assertInstanceOf(GroupDTO::class, $result);

        $json = $result->jsonSerialize();

        self::assertSame('Baddaren', $json['name']);
        self::assertSame('För nybörjare', $json['description']);
        self::assertSame('Mariebad', $json['venue']);
        self::assertSame(1, $json['active']);
        self::assertSame(1, $json['competitive']);
        self::assertNotNull($json['updatedAt']);
    }

    public function testHandlePropagatesExceptionFromRepository() : void {
        $repository = $this->createMock(GroupRepository::class);

        $command = UpdateGroupCommand::fromRequest([
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
            ->method('getById')
            ->with($command->id)
            ->willThrowException(
                new InvalidArgumentException('Group saknas'),
            );

        $handler = new class($repository) extends UpdateGroupHandler {
            public function __construct(GroupRepository $repository) {
                $this->repository = $repository;
            }
        };

        self::expectException(InvalidArgumentException::class);

        $handler->handle($command);
    }

    private function createGroup() : Group {
        return new Group(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
            new GroupLevelId('12345678-9012-3456-7890-123456789012'),
            'Pingvinen',
            'För nybörjare',
            Venue::MARIEBAD,
            1,
            1,
            new DateTimeValue('2026-01-01 10:00:00'),
            null,
        );
    }
}
