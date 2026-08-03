<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use App\Application\Commands\GroupLeader\GroupLeaderCommand;
use App\Domain\Entities\GroupLeader;
use App\Domain\Enums\Role;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;
use PHPUnit\Framework\TestCase;

final class GroupLeaderTest extends TestCase {
    private GroupId $groupId;

    private UserId $userId;

    private DateTimeValue $createdAt;

    public function testConstructorAndGetters() : void {
        $groupLeader = new GroupLeader(
            $this->groupId,
            $this->userId,
            Role::LEADER,
            $this->createdAt,
            null,
        );

        self::assertSame($this->groupId, $groupLeader->getGroupId(), );

        self::assertSame($this->userId, $groupLeader->getUserId(), );

        self::assertSame(Role::LEADER, $groupLeader->getRole(), );

        self::assertSame($this->createdAt, $groupLeader->getCreatedAt(), );

        self::assertNull($groupLeader->getUpdatedAt(), );
    }

    public function testSetRole() : void {
        $groupLeader = new GroupLeader(
            $this->groupId,
            $this->userId,
            Role::ASSISTANT,
            $this->createdAt,
            null,
        );

        $groupLeader->setRole(Role::LEADER);

        self::assertSame(Role::LEADER, $groupLeader->getRole(), );
    }

    public function testSetUpdatedAt() : void {
        $groupLeader = new GroupLeader(
            $this->groupId,
            $this->userId,
            Role::LEADER,
            $this->createdAt,
            null,
        );

        $updatedAt = new DateTimeValue('2026-07-01 10:00:00', );

        $groupLeader->setUpdatedAt($updatedAt);

        self::assertSame($updatedAt, $groupLeader->getUpdatedAt(), );
    }

    public function testFromDBRow() : void {
        $row = [
            'group_id' => '550e8400-e29b-41d4-a716-446655440000',
            'user_id' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => Role::LEADER->value,
            'created_at' => '2026-06-10 10:00:00',
            'updated_at' => null,
        ];

        $groupLeader = GroupLeader::fromDBRow($row);

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $groupLeader->getGroupId()->toString(), );

        self::assertSame('660e8400-e29b-41d4-a716-446655440000', $groupLeader->getUserId()->toString(), );

        self::assertSame(Role::LEADER, $groupLeader->getRole(), );
    }

    public function testFromCommand() : void {
        $command = GroupLeaderCommand::fromRequest([
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => Role::LEADER->value,
        ]);

        $groupLeader = GroupLeader::fromCommand($command);

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $groupLeader->getGroupId()->toString(), );

        self::assertSame('660e8400-e29b-41d4-a716-446655440000', $groupLeader->getUserId()->toString(), );

        self::assertSame(Role::LEADER, $groupLeader->getRole(), );

        self::assertNull($groupLeader->getUpdatedAt(), );
    }

    public function testAsDBRow() : void {
        $groupLeader = new GroupLeader(
            $this->groupId,
            $this->userId,
            Role::LEADER,
            $this->createdAt,
            null,
        );

        $row = $groupLeader->asDBRow();

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $row['group_id'], );

        self::assertSame('660e8400-e29b-41d4-a716-446655440000', $row['user_id'], );

        self::assertSame(Role::LEADER->value, $row['role'], );

        self::assertSame('2026-06-10 10:00:00', $row['created_at'], );

        self::assertNull($row['updated_at'], );
    }

    public function testJsonSerialize() : void {
        $groupLeader = new GroupLeader(
            $this->groupId,
            $this->userId,
            Role::LEADER,
            $this->createdAt,
            null,
        );

        $data = $groupLeader->jsonSerialize();

        self::assertSame('550e8400-e29b-41d4-a716-446655440000', $data['group_id'], );

        self::assertSame('660e8400-e29b-41d4-a716-446655440000', $data['user_id'], );

        self::assertSame(Role::LEADER->value, $data['role'], );

        self::assertSame('2026-06-10 10:00:00', $data['createdAt'], );

        self::assertNull($data['updatedAt'], );
    }

    protected function setUp() : void {
        $this->groupId = new GroupId('550e8400-e29b-41d4-a716-446655440000', );

        $this->userId = new UserId('660e8400-e29b-41d4-a716-446655440000', );

        $this->createdAt = new DateTimeValue('2026-06-10 10:00:00', );
    }
}
