<?php

declare(strict_types=1);

namespace Domain\Entities;

use App\Domain\Entities\Group;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;
use PHPUnit\Framework\TestCase;

final class GroupTest extends TestCase {
    private GroupId $id;

    private DateTimeValue $createdAt;

    public function testConstructorAndGetters() : void {
        $group = new Group(
            $this->id,
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            'Arena',
            1,
            1,
            $this->createdAt,
            null,
        );

        self::assertSame($this->id, $group->getId());
        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $group->getGroupLevelId()->toString());
        self::assertSame('Baddaren', $group->getName());
        self::assertSame('För nybörjare', $group->getDescription());
        self::assertSame('Arena', $group->getVenue());
        self::assertSame(1, $group->getActive());
        self::assertSame(1, $group->getCompetitive());
        self::assertSame($this->createdAt, $group->getCreatedAt());
        self::assertNull($group->getUpdatedAt());
    }

    public function testSetters() : void {
        $group = new Group(
            $this->id,
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            'Arena',
            1,
            1,
            $this->createdAt,
            null,
        );

        $group->setGroupLevelId(new GroupLevelId('550e8400-e29b-41d4-a716-446655440001'));
        $group->setName('Pingvinen');
        $group->setDescription('Kan simma själv');
        $group->setVenue('Stadion');
        $group->setActive(0);
        $group->setCompetitive(0);

        self::assertSame('550e8400-e29b-41d4-a716-446655440001', $group->getGroupLevelId()->toString());
        self::assertSame('Pingvinen', $group->getName());
        self::assertSame('Kan simma själv', $group->getDescription());
        self::assertSame('Stadion', $group->getVenue());
        self::assertSame(0, $group->getActive());
        self::assertSame(0, $group->getCompetitive());
    }

    public function testSetUpdatedAt() : void {
        $group = new Group(
            $this->id,
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            'Arena',
            1,
            1,
            $this->createdAt,
            null,
        );

        $updatedAt = new DateTimeValue('2026-07-15 10:00:00');

        $group->setUpdatedAt($updatedAt);

        self::assertSame($updatedAt, $group->getUpdatedAt());
    }

    public function testFromDBRow() : void {
        $row = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'group_level_id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'venue' => 'Arena',
            'active' => 1,
            'competitive' => 1,
            'created_at' => '2026-07-15 10:00:00',
            'updated_at' => null,
        ];

        $group = Group::fromDBRow($row);

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $group->getId()->toString(), );
        self::assertSame('Baddaren', $group->getName());
        self::assertSame('För nybörjare', $group->getDescription());
        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $group->getGroupLevelId()->toString());
        self::assertSame('Arena', $group->getVenue());
        self::assertSame(1, $group->getActive());
        self::assertSame(1, $group->getCompetitive());
    }

    public function testAsDBRow() : void {
        $group = new Group(
            $this->id,
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            'Arena',
            1,
            1,
            $this->createdAt,
            null,
        );

        $row = $group->asDBRow();

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $row['id']);
        self::assertSame('Baddaren', $row['name']);
        self::assertSame('För nybörjare', $row['description']);
        self::assertSame('Arena', $row['venue']);
        self::assertSame(1, $row['active']);
        self::assertSame(1, $row['competitive']);
        self::assertSame('2026-07-15 10:00:00', $row['created_at']);
        self::assertNull($row['updated_at']);
    }

    public function testJsonSerialize() : void {
        $group = new Group(
            $this->id,
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            'Arena',
            1,
            1,
            $this->createdAt,
            null,
        );

        $data = $group->jsonSerialize();

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $data['id']);
        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $data['groupLevelId']);
        self::assertSame('Baddaren', $data['name']);
        self::assertSame('För nybörjare', $data['description']);
        self::assertSame('Arena', $data['venue']);
        self::assertSame(1, $data['active']);
        self::assertSame(1, $data['competitive']);
        self::assertSame('2026-07-15 10:00:00', $data['createdAt']);
        self::assertNull($data['updatedAt']);
    }

    protected function setUp() : void {
        $this->id = new GroupId(
            '550e8400-e29b-41d4-a716-446655440000',
        );

        $this->createdAt = new DateTimeValue(
            '2026-07-15 10:00:00',
        );
    }
}
