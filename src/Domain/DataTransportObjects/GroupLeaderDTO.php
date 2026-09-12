<?php

declare(strict_types=1);

namespace App\Domain\DataTransportObjects;

use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\UserId;

class GroupLeaderDTO implements \JsonSerializable {
    public function __construct(
        private UserId $userId,
        private string $firstName,
        private string $lastName,
        private GroupId $groupId,
        private string $name,
        private string $role,
    ) {
    }

    /**
     * @param string[] $row
     */
    public static function fromDBRow(array $row) : self {
        return new self(
            new UserId($row['user_id']),
            $row['first_name'],
            $row['last_name'],
            new GroupId($row['group_id']),
            $row['name'],
            $row['role'],
        );
    }

    /**
     * @return string[]
     */
    public function jsonSerialize() : array {
        return [
            'userId' => $this->userId->toString(),
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'groupId' => $this->groupId->toString(),
            'name' => $this->name,
            'role' => $this->role,
        ];
    }
}
