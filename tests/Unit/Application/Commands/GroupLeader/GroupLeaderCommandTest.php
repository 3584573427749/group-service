<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands\GroupLeader;

use App\Application\Commands\GroupLeader\GroupLeaderCommand;
use PHPUnit\Framework\TestCase;

final class GroupLeaderCommandTest extends TestCase {
    public function testFromRequestMapsValuesCorrectly() : void {
        $data = [
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => 'Ledare',
        ];

        $command = GroupLeaderCommand::fromRequest($data);

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $command->groupId);

        self::assertSame('660e8400-e29b-41d4-a716-446655440000', $command->userId);

        self::assertSame('Ledare', $command->role);
    }
}
