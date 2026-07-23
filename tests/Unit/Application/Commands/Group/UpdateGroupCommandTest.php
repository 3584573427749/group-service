<?php

declare(strict_types=1);

namespace Application\Commands\Group;

use App\Application\Commands\Group\UpdateGroupCommand;
use PHPUnit\Framework\TestCase;

final class UpdateGroupCommandTest extends TestCase {
    public function testFromRequestMapsValuesCorrectly() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '12345678-9012-3456-7890-123456789012',
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'venue' => 'arena',
            'active' => 1,
            'competitive' => 1,
        ];

        $command = UpdateGroupCommand::fromRequest($data);

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $command->id->toString());
        self::assertSame('12345678-9012-3456-7890-123456789012', $command->groupLevelId->toString());
        self::assertSame('Baddaren', $command->name);
        self::assertSame('För nybörjare', $command->description);
        self::assertSame('Arena', $command->venue);
        self::assertSame(1, $command->active);
        self::assertSame(1, $command->competitive);
    }

    public function testNameIsTrimmedAndNormalized() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '12345678-9012-3456-7890-123456789012',
            'name' => '  bADdArEn  ',
            'description' => 'För nybörjare',
            'venue' => 'arena',
            'active' => 1,
            'competitive' => 1,
        ];

        $command = UpdateGroupCommand::fromRequest($data);

        self::assertSame('Baddaren', $command->name);
    }

    public function testVenueIsTrimmedAndNormalized() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '12345678-9012-3456-7890-123456789012',
            'name' => 'Baddaren',
            'description' => 'För nybörjare',
            'venue' => '    arEna   ',
            'active' => 1,
            'competitive' => 1,
        ];

        $command = UpdateGroupCommand::fromRequest($data);

        self::assertSame('Arena', $command->venue);
    }

    public function testDescriptionIsTrimmed() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '12345678-9012-3456-7890-123456789012',
            'name' => 'Baddaren',
            'venue' => 'arena',
            'active' => 1,
            'competitive' => 1,
            'description' => '  För nybörjare  ',
        ];

        $command = UpdateGroupCommand::fromRequest($data);

        self::assertSame('För nybörjare', $command->description);
    }

    public function testEmptyDescriptionBecomesEmptyString() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '12345678-9012-3456-7890-123456789012',
            'name' => 'Baddaren',
            'venue' => 'arena',
            'active' => 1,
            'competitive' => 1,
        ];

        $command = UpdateGroupCommand::fromRequest($data);

        self::assertSame('', $command->description);
    }
}
