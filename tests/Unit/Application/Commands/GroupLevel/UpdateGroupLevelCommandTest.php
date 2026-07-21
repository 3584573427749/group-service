<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\GroupLevel;

use App\Application\Commands\GroupLevel\UpdateGroupLevelCommand;
use PHPUnit\Framework\TestCase;

final class UpdateGroupLevelCommandTest extends TestCase {
    public function testFromRequestMapsValuesCorrectly() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'sortOrder' => 1,
        ];

        $command = UpdateGroupLevelCommand::fromRequest($data);

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $command->id->toString(),
        );
        self::assertSame('Baddaren', $command->name);
        self::assertSame('För nybörjare', $command->description);
        self::assertSame(1, $command->sortOrder);
    }

    public function testNameIsTrimmedAndNormalized() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => '  bADdArEn  ',
            'description' => 'För nybörjare',
            'sortOrder' => 1,
        ];

        $command = UpdateGroupLevelCommand::fromRequest($data);

        self::assertSame('Baddaren', $command->name);
    }

    public function testDescriptionIsTrimmed() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'description' => '  För nybörjare  ',
            'sortOrder' => 1,
        ];

        $command = UpdateGroupLevelCommand::fromRequest($data);

        self::assertSame('För nybörjare', $command->description);
    }

    public function testEmptyDescriptionBecomesEmptyString() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'sortOrder' => 1,
        ];

        $command = UpdateGroupLevelCommand::fromRequest($data);

        self::assertSame('', $command->description);
    }

    public function testHandlesMinimalValidInput() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'A',
            'description' => '',
            'sortOrder' => 1,
        ];

        $command = UpdateGroupLevelCommand::fromRequest($data);

        self::assertSame(
            '550e8400-e29b-41d4-a716-446655440000',
            $command->id->toString(),
        );
        self::assertSame('A', $command->name);
        self::assertSame('', $command->description);
        self::assertSame(1, $command->sortOrder);
    }
}
