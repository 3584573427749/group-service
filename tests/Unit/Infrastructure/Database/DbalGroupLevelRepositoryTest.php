<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Database;

use App\Domain\Entities\GroupLevel;
use App\Domain\Exception\NotFoundException;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;
use App\Infrastructure\Database\DbalGroupLevelRepository;

final class DbalGroupLevelRepositoryTest extends DatabaseBaseTestCase {
    private DbalGroupLevelRepository $repository;

    public function testSaveInsertsNewGroupLevel() : void {
        $groupLevel = $this->createGroupLevel();

        $this->repository->save($groupLevel);

        $row = $this->connection->fetchAssociative(
            'SELECT * FROM group_levels WHERE id = :id',
            ['id' => $groupLevel->getId()->toString()],
        );

        self::assertNotFalse($row);
        self::assertSame('Baddaren', $row['name']);
        self::assertSame('För nybörjare', $row['description']);
        self::assertSame(1, (int) $row['sort_order']);
    }

    public function testSaveUpdatesExistingGroupLevel() : void {
        $groupLevel = $this->createGroupLevel();

        $this->connection->insert(
            'group_levels',
            $groupLevel->asDBRow(),
        );

        $groupLevel->setName('Pingvinen');
        $groupLevel->setDescription('Kan simma själv');
        $groupLevel->setSortOrder(2);
        $groupLevel->setUpdatedAt(
            new DateTimeValue('2026-01-02 10:00:00'),
        );

        $this->repository->save($groupLevel);

        $row = $this->connection->fetchAssociative(
            'SELECT * FROM group_levels WHERE id = :id',
            ['id' => $groupLevel->getId()->toString()],
        );

        self::assertNotFalse($row);
        self::assertSame('Pingvinen', $row['name']);
        self::assertSame('Kan simma själv', $row['description']);
        self::assertSame(2, (int) $row['sort_order']);
    }

    public function testGetAllReturnsEmptyArrayWhenNoGroupLevelsExist() : void {
        $result = $this->repository->getAll();

        self::assertSame([], $result);
    }

    public function testGetAllReturnsGroupLevels() : void {
        $this->seed('group_levels', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'sort_order' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
            [
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'name' => 'Pingvinen',
                'description' => 'Kan simma själv',
                'sort_order' => 2,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $result = $this->repository->getAll();

        self::assertCount(2, $result);

        self::assertSame('Baddaren', $result[0]->getName());
        self::assertSame('Pingvinen', $result[1]->getName());
    }

    public function testGetByIdReturnsGroupLevel() : void {
        $this->seed('group_levels', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'sort_order' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $result = $this->repository->getById(
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
        );

        self::assertInstanceOf(GroupLevel::class, $result);
        self::assertSame('Baddaren', $result->getName());
        self::assertSame('För nybörjare', $result->getDescription());
        self::assertSame(1, $result->getSortOrder());
    }

    public function testGetByIdThrowsExceptionWhenGroupLevelDoesNotExist() : void {
        $this->expectException(NotFoundException::class);

        $this->repository->getById(
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
        );
    }

    public function testDeleteRemovesGroupLevel() : void {
        $this->seed('group_levels', [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'name' => 'Baddaren',
                'description' => 'För nybörjare',
                'sort_order' => 1,
                'created_at' => '2026-01-01 10:00:00',
                'updated_at' => null,
            ],
        ]);

        $this->repository->delete(
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
        );

        $row = $this->connection->fetchAssociative(
            'SELECT * FROM group_levels WHERE id = :id',
            ['id' => '550e8400-e29b-41d4-a716-446655440000'],
        );

        self::assertFalse($row);
    }

    private function createGroupLevel(
        ?DateTimeValue $updatedAt = null,
    ) : GroupLevel {
        return new GroupLevel(
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            1,
            new DateTimeValue('2026-01-01 10:00:00'),
            $updatedAt,
        );
    }

    protected function setUp() : void {
        parent::setUp();

        $this->loadSchema('group_levels');

        $this->repository = new DbalGroupLevelRepository(
            $this->connection,
        );
    }
}
