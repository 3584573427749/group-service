<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use App\Domain\Entities\GroupLevel;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;
use PHPUnit\Framework\TestCase;

final class GroupLevelTest extends TestCase {
    private GroupLevelId $id;

    private DateTimeValue $createdAt;

    public function testConstructorAndGetters() : void {
        $groupLevel = new GroupLevel(
            $this->id,
            'Baddaren',
            'För nybörjare',
            1,
            $this->createdAt,
            null,
        );

        self::assertSame($this->id, $groupLevel->getId());
        self::assertSame('Baddaren', $groupLevel->getName());
        self::assertSame('För nybörjare', $groupLevel->getDescription());
        self::assertSame(1, $groupLevel->getSortOrder());
        self::assertSame($this->createdAt, $groupLevel->getCreatedAt());
        self::assertNull($groupLevel->getUpdatedAt());
    }

    public function testSetters() : void {
        $groupLevel = new GroupLevel(
            $this->id,
            'Baddaren',
            'För nybörjare',
            1,
            $this->createdAt,
            null,
        );

        $groupLevel->setName('Pingvinen');
        $groupLevel->setDescription('Kan simma själv');
        $groupLevel->setSortOrder(2);

        self::assertSame('Pingvinen', $groupLevel->getName());
        self::assertSame('Kan simma själv', $groupLevel->getDescription());
        self::assertSame(2, $groupLevel->getSortOrder());
    }

    public function testSetUpdatedAt() : void {
        $groupLevel = new GroupLevel(
            $this->id,
            'Baddaren',
            'För nybörjare',
            1,
            $this->createdAt,
            null,
        );

        $updatedAt = new DateTimeValue('2026-07-15 10:00:00');

        $groupLevel->setUpdatedAt($updatedAt);

        self::assertSame($updatedAt, $groupLevel->getUpdatedAt());
    }

    public function testFromDBRow() : void {
        $row = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'sort_order' => 1,
            'created_at' => '2026-07-15 10:00:00',
            'updated_at' => null,
        ];

        $groupLevel = GroupLevel::fromDBRow($row);

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $groupLevel->getId()->toString(),
        );
        self::assertSame('Baddaren', $groupLevel->getName());
        self::assertSame('För nybörjare', $groupLevel->getDescription());
        self::assertSame(1, $groupLevel->getSortOrder());
    }

    public function testAsDBRow() : void {
        $groupLevel = new GroupLevel(
            $this->id,
            'Baddaren',
            'För nybörjare',
            1,
            $this->createdAt,
            null,
        );

        $row = $groupLevel->asDBRow();

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $row['id'],
        );
        self::assertSame('Baddaren', $row['name']);
        self::assertSame('För nybörjare', $row['description']);
        self::assertSame(1, $row['sort_order']);
        self::assertSame('2026-07-15 10:00:00', $row['created_at']);
        self::assertNull($row['updated_at']);
    }

    public function testJsonSerialize() : void {
        $groupLevel = new GroupLevel(
            $this->id,
            'Baddaren',
            'För nybörjare',
            1,
            $this->createdAt,
            null,
        );

        $data = $groupLevel->jsonSerialize();

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $data['id'],
        );
        self::assertSame('Baddaren', $data['name']);
        self::assertSame('För nybörjare', $data['description']);
        self::assertSame(1, $data['sortOrder']);
        self::assertSame('2026-07-15 10:00:00', $data['createdAt']);
        self::assertNull($data['updatedAt']);
    }

    protected function setUp() : void {
        $this->id = new GroupLevelId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $this->createdAt = new DateTimeValue(
            '2026-07-15 10:00:00',
        );
    }
}
