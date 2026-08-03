<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Application\Commands\GroupLeader\GroupLeaderCommand;
use App\Domain\Enums\Role;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;
use JsonSerializable;

class GroupLeader implements JsonSerializable {
    public function __construct(
        private GroupId $groupId,
        private UserId $userId,
        private Role $role,
        private DateTimeValue $createdAt,
        private ?DateTimeValue $updatedAt,
    ) {
    }

    public function getCreatedAt() : DateTimeValue {
        return $this->createdAt;
    }

    public function getGroupId() : GroupId {
        return $this->groupId;
    }

    public function getUserId() : UserId {
        return $this->userId;
    }

    public function getRole() : Role {
        return $this->role;
    }

    public function getUpdatedAt() : ?DateTimeValue {
        return $this->updatedAt;
    }

    public function setRole(Role $role) : void {
        $this->role = $role;
    }

    public function setUpdatedAt(?DateTimeValue $updatedAt) : void {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param array<string, mixed> $row
     */
    public static function fromDBRow(array $row) : self {
        return new self(
            new GroupId($row['group_id']),
            new UserId($row['user_id']),
            Role::from($row['role']),
            new DateTimeValue($row['created_at']),
            !empty($row['updated_at']) ? new DateTimeValue($row['updated_at']) : null,
        );
    }

    /**
     * fromCommand används endast för att skapa _NYA_ GroupLeadera
     */
    public static function fromCommand(GroupLeaderCommand $command) : self {
        return new self(
            new GroupId($command->groupId),
            new UserId($command->userId),
            Role::from($command->role),
            new DateTimeValue('now'),
            null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function asDBRow() : array {
        return [
            'group_id' => $this->groupId->toString(),
            'user_id' => $this->userId->toString(),
            'role' => $this->role->value,
            'created_at' => $this->createdAt->toString(),
            'updated_at' => $this->updatedAt ? $this->updatedAt->toString() : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize() : array {
        return [
            'group_id' => $this->groupId->toString(),
            'user_id' => $this->userId->toString(),
            'role' => $this->role->value,
            'createdAt' => $this->createdAt->toString(),
            'updatedAt' => $this->updatedAt ? $this->updatedAt->toString() : null,
        ];
    }
}
