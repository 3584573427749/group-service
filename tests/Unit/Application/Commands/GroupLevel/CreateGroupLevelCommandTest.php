<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\GroupLevel;

use App\Application\Commands\GroupLevel\CreateGroupLevelCommand;
use PHPUnit\Framework\TestCase;

final class CreateGroupLevelCommandTest extends TestCase {
    public function testFromRequestMapsValuesCorrectly() : void {
        $data = [
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'sortOrder' => 1,
        ];

        $command = CreateGroupLevelCommand::fromRequest($data);

        self::assertSame('Baddaren', $command->name);
        self::assertSame('För nybörjare', $command->description);
        self::assertSame(1, $command->sortOrder);
    }

    public function testNameIsTrimmedAndNormalized() : void {
        $data = [
            'name' => '  bADdArEn  ',
            'description' => 'För nybörjare',
            'sortOrder' => 1,
        ];

        $command = CreateGroupLevelCommand::fromRequest($data);

        self::assertSame('Baddaren', $command->name);
    }

    public function testDescriptionIsTrimmed() : void {
        $data = [
            'name' => 'Baddaren',
            'description' => '  För nybörjare  ',
            'sortOrder' => 1,
        ];

        $command = CreateGroupLevelCommand::fromRequest($data);

        self::assertSame('För nybörjare', $command->description);
    }

    public function testEmptyDescriptionBecomesEmptyString() : void {
        $data = [
            'name' => 'Baddaren',
            'sortOrder' => 1,
        ];

        $command = CreateGroupLevelCommand::fromRequest($data);

        self::assertSame('', $command->description);
    }

    public function testHandlesMinimalValidInput() : void {
        $data = [
            'name' => 'A',
            'description' => '',
            'sortOrder' => 1,
        ];

        $command = CreateGroupLevelCommand::fromRequest($data);

        self::assertSame('A', $command->name);
        self::assertSame('', $command->description);
        self::assertSame(1, $command->sortOrder);
    }
}
