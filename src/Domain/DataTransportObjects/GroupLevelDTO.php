<?php

declare(strict_types=1);

namespace App\Domain\DataTransportObjects;

use App\Domain\Entities\GroupLevel;
use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\GroupLevelId;

class GroupLevelDTO implements \JsonSerializable {
    public function __construct(
        private GroupLevelId $id,
        private string $name,
        private string $description,
        private int $sortOrder,
        private DateTimeValue $createdAt,
        private ?DateTimeValue $updatedAt,
    ) {
    }

    public static function fromEntity(GroupLevel $groupLevel) : self {
        return new self(
            $groupLevel->getId(),
            $groupLevel->getName(),
            $groupLevel->getDescription(),
            $groupLevel->getSortOrder(),
            $groupLevel->getCreatedAt(),
            $groupLevel->getUpdatedAt() ?? null,
        );

    }

    public function jsonSerialize() : mixed {
        return [
            'id' => $this->id->toString(),
            'name' => $this->name,
            'description' => $this->description,
            'sortOrder' => $this->sortOrder,
            'createdAt' => $this->createdAt->toISOString(),
            'updatedAt' => $this->updatedAt ? $this->updatedAt->toISOString() : null,
        ];
    }
}
