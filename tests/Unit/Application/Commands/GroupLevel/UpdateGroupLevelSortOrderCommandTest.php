<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\GroupLevel;

use App\Application\Commands\GroupLevel\UpdateGroupLevelSortOrderCommand;
use App\Domain\DataTransportObjects\GroupLevelSortOrderDTO;
use PHPUnit\Framework\TestCase;

final class UpdateGroupLevelSortOrderCommandTest extends TestCase {
    public function testFromRequestMapsValuesCorrectly() : void {
        $data = [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => 1,
            ],
            [
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => 2,
            ],
        ];

        $command = UpdateGroupLevelSortOrderCommand::fromRequest($data);

        self::assertCount(
            2,
            $command->command,
        );

        self::assertInstanceOf(
            GroupLevelSortOrderDTO::class,
            $command->command[0],
        );

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $command->command[0]->getId()->toString(),
        );

        self::assertSame(
            1,
            $command->command[0]->getSortOrder(),
        );

        self::assertSame(
            '660e8400-e29b-41d4-a716-446655440000',
            $command->command[1]->getId()->toString(),
        );

        self::assertSame(
            2,
            $command->command[1]->getSortOrder(),
        );
    }

    public function testSortOrderStringIsConvertedToInteger() : void {
        $data = [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => '5',
            ],
        ];

        $command = UpdateGroupLevelSortOrderCommand::fromRequest($data);

        self::assertSame(
            5,
            $command->command[0]->getSortOrder(),
        );
    }

    public function testHandlesEmptyArray() : void {
        $command = UpdateGroupLevelSortOrderCommand::fromRequest([]);

        self::assertSame(
            [],
            $command->command,
        );
    }
}
