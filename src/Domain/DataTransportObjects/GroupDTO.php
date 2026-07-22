<?php

declare(strict_types=1);

namespace App\Domain\DataTransportObjects;

use App\Domain\Entities\Group;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;

class GroupDTO implements \JsonSerializable {
    public function __construct(
        private GroupId $id,
        private GroupLevelId $groupLevelId,
        private string $name,
        private string $description,
        private int $active,
        private int $competitive,
        private DateTimeValue $createdAt,
        private ?DateTimeValue $updatedAt,
    ) {
    }

    public static function fromEntity(Group $group) : self {
        return new self(
            $group->getId(),
            $group->getGroupLevelId(),
            $group->getName(),
            $group->getDescription(),
            $group->getActive(),
            $group->getCompetitive(),
            $group->getCreatedAt(),
            $group->getUpdatedAt() ?? null,
        );

    }

    public function jsonSerialize() : mixed {
        return [
            'id' => $this->id->toString(),
            'groupLevelId' => $this->groupLevelId->toString(),
            'name' => $this->name,
            'description' => $this->description,
            'active' => $this->active,
            'competitive' => $this->competitive,
            'createdAt' => $this->createdAt->toISOString(),
            'updatedAt' => $this->updatedAt ? $this->updatedAt->toISOString() : null,
        ];
    }
}
