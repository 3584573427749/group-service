<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\DataTransportObjects;

use App\Domain\DataTransportObjects\GroupLevelDTO;
use App\Domain\Entities\GroupLevel;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;
use PHPUnit\Framework\TestCase;

final class GroupLevelDTOTest extends TestCase {
    public function testFromEntityCreatesDto() : void {
        $groupLevel = new GroupLevel(
            new GroupLevelId('550e8400-e29b-41d4-a716-446655440000'),
            'Baddaren',
            'För nybörjare',
            1,
            new DateTimeValue('2026-01-01T10:00:00+00:00'),
            null,
        );

        $dto = GroupLevelDTO::fromEntity($groupLevel);

        $data = $dto->jsonSerialize();

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $data['id'],
        );
        self::assertSame('Baddaren', $data['name']);
        self::assertSame('För nybörjare', $data['description']);
        self::assertSame(1, $data['sortOrder']);
        self::assertSame('2026-01-01T10:00:00+00:00', $data['createdAt']);
    }

    public function testJsonSerializeReturnsCorrectStructure() : void {
        $groupLevel = new GroupLevel(
            new GroupLevelId('660e8400-e29b-41d4-a716-446655440000'),
            'Pingvinen',
            'Kan simma själv',
            2,
            new DateTimeValue('2026-01-01T10:00:00+00:00'),
            null,
        );

        $dto = GroupLevelDTO::fromEntity($groupLevel);

        $data = $dto->jsonSerialize();

        self::assertArrayHasKey('id', $data);
        self::assertArrayHasKey('name', $data);
        self::assertArrayHasKey('description', $data);
        self::assertArrayHasKey('sortOrder', $data);
        self::assertArrayHasKey('createdAt', $data);
        self::assertArrayHasKey('updatedAt', $data);
    }

    public function testJsonSerializeIncludesUpdatedAt() : void {
        $groupLevel = new GroupLevel(
            new GroupLevelId('770e8400-e29b-41d4-a716-446655440000'),
            'Hajen',
            'Tävlingsförberedande',
            3,
            new DateTimeValue('2026-01-01T10:00:00+00:00'),
            new DateTimeValue('2026-01-02T10:00:00+00:00'),
        );

        $dto = GroupLevelDTO::fromEntity($groupLevel);

        $data = $dto->jsonSerialize();

        self::assertNotNull($data['updatedAt']);
    }
}
