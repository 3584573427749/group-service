<?php

declare(strict_types=1);

namespace App\Application\Commands\Group;

use App\Domain\ValueObjects\GroupLevelId;

class CreateGroupCommand {
    public function __construct(
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
            groupLevelId: new GroupLevelId($data['groupLevelId'] ?? ''),
            name: $data['name']
            |>trim(...)
            |>mb_strtolower(...)
            |>mb_ucfirst(...),
            description: trim($data['description'] ?? ''),
            venue: $data['venue'] ?? ''
            |>trim(...)
            |>mb_strtolower(...)
            |>mb_ucfirst(...),
            active: (int) filter_var($data['active'], FILTER_VALIDATE_INT),
            competitive: (int) filter_var($data['competitive'], FILTER_VALIDATE_INT),
        );
    }
}
