<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\DataTransferObjects;

use App\Domain\DataTransportObjects\GroupDTO;
use App\Domain\Entities\Group;
use App\Domain\Enums\Venue;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;
use PHPUnit\Framework\TestCase;

final class GroupDTOTest extends TestCase {
    public function testFromEntityCreatesDto() : void {
        $group = new Group(
            new GroupId('550e8400-e29b-41d4-a716-446655440000'),
            new GroupLevelId('12345678-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            Venue::ALANDS_IDROTTCENTER,
            1,
            1,
            new DateTimeValue('2026-01-01T10:00:00+00:00'),
            null,
        );

        $dto = GroupDTO::fromEntity($group);

        $data = $dto->jsonSerialize();

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $data['id'],
        );
        self::assertSame('Baddaren', $data['name']);
        self::assertSame('För nybörjare', $data['description']);
        self::assertSame('12345678-e29b-41d4-a716-446655440000', $data['groupLevelId']);
        self::assertSame('Ålands Idrottscenter', $data['venue']);
        self::assertSame(1, $data['active']);
        self::assertSame(1, $data['competitive']);
        self::assertSame('2026-01-01T10:00:00+00:00', $data['createdAt']);
    }

    public function testJsonSerializeReturnsCorrectStructure() : void {
        $group = new Group(
            new GroupId('660e8400-e29b-41d4-a716-446655440000'),
            new GroupLevelId('12345678-e29b-41d4-a716-446655440000'),
            'Pingvinen',
            'Kan simma själv',
            Venue::ALANDS_IDROTTCENTER,
            1,
            1,
            new DateTimeValue('2026-01-01T10:00:00+00:00'),
            null,
        );

        $dto = GroupDTO::fromEntity($group);

        $data = $dto->jsonSerialize();

        self::assertArrayHasKey('id', $data);
        self::assertArrayHasKey('groupLevelId', $data);
        self::assertArrayHasKey('name', $data);
        self::assertArrayHasKey('description', $data);
        self::assertArrayHasKey('venue', $data);
        self::assertArrayHasKey('active', $data);
        self::assertArrayHasKey('competitive', $data);
        self::assertArrayHasKey('createdAt', $data);
        self::assertArrayHasKey('updatedAt', $data);
    }

    public function testJsonSerializeIncludesUpdatedAt() : void {
        $group = new Group(
            new GroupId('770e8400-e29b-41d4-a716-446655440000'),
            new GroupLevelId('12345678-e29b-41d4-a716-446655440000'),
            'Hajen',
            'Tävlingsförberedande',
            Venue::ALANDS_IDROTTCENTER,
            1,
            1,
            new DateTimeValue('2026-01-01T10:00:00+00:00'),
            new DateTimeValue('2026-01-02T10:00:00+00:00'),
        );

        $dto = GroupDTO::fromEntity($group);

        $data = $dto->jsonSerialize();

        self::assertNotNull($data['updatedAt']);
    }
}
