<?php

declare(strict_types=1);

namespace Infrastructure\Database;

use App\Domain\Entities\Group;
use App\Domain\Enums\Venue;
use App\Domain\Exception\NotFoundException;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;
use App\Infrastructure\Database\DbalGroupRepository;
use Tests\Unit\Infrastructure\Database\DatabaseBaseTestCase;

final class DbalGroupRepositoryTest extends DatabaseBaseTestCase {
    private DbalGroupRepository $repository;

    public function testSaveInsertsNewGroup() : void {
        $group = $this->createGroup();

        $this->repository->save($group);

        $row = $this->connection->fetchAssociative(
            'SELECT * FROM groups WHERE id = :id',
            ['id' => $group->getId()->toString()],
        );

        self::assertNotFalse($row);
        self::assertSame('Baddaren', $row['name']);
        self::assertSame('För nybörjare', $row['description']);
        self::assertSame('Ålands Idrottscenter', $row['venue']);
        self::assertSame(1, $row['active']);
        self::assertSame(1, $row['competitive']);
    }

    public function testSaveUpdatesExistingGroup() : void {
        $group = $this->createGroup();

        $this->connection->insert(
            'groups',
            $group->asDBRow(),
        );

        $group->setName('Pingvinen');
        $group->setDescription('Kan simma själv');
        $group->setActive(0);
        $group->setCompetitive(0);
        $group->setUpdatedAt(
            new DateTimeValue('2026-01-02 10:00:00'),
        );

        $this->repository->save($group);

        $row = $this->connection->fetchAssociative(
            'SELECT * FROM groups WHERE id = :id',
            ['id' => $group->getId()->toString()],
        );

        self::assertNotFalse($row);
        self::assertSame('Pingvinen', $row['name']);
        self::assertSame('Kan simma själv', $row['description']);
        self::assertSame(0, (int) $row['active']);
        self::assertSame(0, (int) $row['competitive']);
    }

    public function testGetAllReturnsEmptyArrayWhenNoGroupsExist() : void {
        $result = $this->repository->getAll();

        self::assertSame([], $result);
    }

    public function testGetAllReturnsGroups() : void {
        $this->seed('groups', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'group_level_id' => '660e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
            [
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'group_level_id' => '660e8400-e29b-41d4-a716-446655440000',
                'name' => 'Pingvinen',
                'description' => 'Kan simma själv',
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $result = $this->repository->getAll();

        self::assertCount(2, $result);

        self::assertSame('Baddaren', $result[0]->getName());
        self::assertSame('Pingvinen', $result[1]->getName());
    }

    public function testGetByIdReturnsGroup() : void {
        $this->seed('groups', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'group_level_id' => '660e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $result = $this->repository->getById(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
        );

        self::assertInstanceOf(Group::class, $result);
        self::assertSame('Baddaren', $result->getName());
        self::assertSame('För nybörjare', $result->getDescription());
        self::assertSame(Venue::MARIEBAD, $result->getVenue());
        self::assertSame(1, $result->getActive());
        self::assertSame(1, $result->getCompetitive());
    }

    public function testGetByIdThrowsExceptionWhenGroupDoesNotExist() : void {
        $this->expectException(NotFoundException::class);

        $this->repository->getById(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
        );
    }

    public function testDeleteRemovesGroup() : void {
        $this->seed('groups', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'group_level_id' => '660e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'venue' => 'Mariebad',
                'active' => 1,
                'competitive' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $this->repository->delete(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
        );

        $row = $this->connection->fetchAssociative(
            'SELECT * FROM groups WHERE id = :id',
            ['id' => '550e8400-e29b-41d4-a716-446655440000'],
        );

        self::assertFalse($row);
    }

    public function testDeleteThrowsExceptionWhenGroupDoesNotExist() : void {
        $this->expectException(NotFoundException::class);

        $this->repository->delete(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
        );
    }

    private function createGroup(
        ?DateTimeValue $updatedAt = null,
    ) : Group {
        return new Group(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
            new GroupLevelId('660e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            Venue::ALANDS_IDROTTCENTER,
            1,
            1,
            new DateTimeValue('2026-01-01 10:00:00'),
            $updatedAt,
        );
    }

    protected function setUp() : void {
        parent::setUp();

        $this->loadSchema('groups');

        $this->repository = new DbalGroupRepository(
            $this->connection,
        );
    }
}
