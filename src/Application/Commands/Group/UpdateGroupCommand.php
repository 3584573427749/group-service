<?php

declare(strict_types=1);

namespace App\Application\Commands\Group;

use App\Domain\ValueObjects\GroupId;
use App\Domain\ValueObjects\GroupLevelId;

class UpdateGroupCommand {
    public function __construct(
        public GroupId $id,
        public GroupLevelId $groupLevelId,
        public string $name,
        public string $description,
        public string $venue,
        public int $active,
        public int $competitive,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromRequest(array $data) : self {
        return new self(
            id: new GroupId($data['id']),
            groupLevelId: new GroupLevelId($data['groupLevelId']),
            name: $data['name']
            |>trim(...)
            |>mb_strtolower(...)
            |>mb_ucfirst(...),
            description: trim($data['description'] ?? ''),
            venue: trim($data['venue'] ?? ''),
            active: (int) filter_var($data['active'], FILTER_VALIDATE_INT),
            competitive: (int) filter_var($data['competitive'], FILTER_VALIDATE_INT),
        );
    }
}
