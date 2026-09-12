<?php

declare(strict_types=1);

namespace App\Domain\DataTransportObjects;

use App\Domain\Entities\GroupLevel;
use App\Domain\ValueObjects\GroupLevelId;

class GroupLevelSortOrderDTO implements \JsonSerializable {
    public function __construct(
        private GroupLevelId $id,
        private int $sortOrder,
    ) {
    }

    public function getId() : GroupLevelId {
        return $this->id;
    }

    public function getSortOrder() : int {
        return $this->sortOrder;
    }

    public static function fromEntity(GroupLevel $groupLevel) : self {
        return new self(
            $groupLevel->getId(),
            $groupLevel->getSortOrder(),
        );

    }

    public function jsonSerialize() : mixed {
        return [
            'id' => $this->id->toString(),
            'sortOrder' => $this->sortOrder,
        ];
    }
}
