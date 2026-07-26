<?php

declare(strict_types=1);

namespace Application\Commands\Group;

use App\Application\Commands\Group\CreateGroupCommand;
use App\Domain\Enums\Venue;
use PHPUnit\Framework\TestCase;

final class CreateGroupCommandTest extends TestCase {
    public function testFromRequestMapsValuesCorrectly() : void {
        $data = [
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 1,
        ];

        $command = CreateGroupCommand::fromRequest($data);

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $command->groupLevelId->toString());
        self::assertSame('Baddaren', $command->name);
        self::assertSame('För nybörjare', $command->description);
        self::assertSame(Venue::MARIEBAD, $command->venue);
        self::assertSame(1, $command->active);
        self::assertSame(1, $command->competitive);
    }

    public function testNameIsTrimmedAndNormalized() : void {
        $data = [
            'name' => '  bADdArEn  ',
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'description' => 'För nybörjare',
            'venue' => Venue::MARIEBAD->value,
            'active' => 1,
            'competitive' => 1,
        ];

        $command = CreateGroupCommand::fromRequest($data);

        self::assertSame('Baddaren', $command->name);
    }

    public function testDescriptionIsTrimmed() : void {
        $data = [
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'venue' => Venue::MARIEBAD->value,
            'active' => 1,
            'competitive' => 1,
            'description' => '  För nybörjare  ',
        ];

        $command = CreateGroupCommand::fromRequest($data);

        self::assertSame('För nybörjare', $command->description);
    }

    public function testEmptyDescriptionBecomesEmptyString() : void {
        $data = [
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'venue' => Venue::MARIEBAD->value,
            'active' => 1,
            'competitive' => 1,
        ];

        $command = CreateGroupCommand::fromRequest($data);

        self::assertSame('', $command->description);
    }
}
