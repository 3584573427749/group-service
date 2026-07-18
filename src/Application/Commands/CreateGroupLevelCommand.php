<?php

declare(strict_types=1);

namespace App\Application\Commands;

class CreateGroupLevelCommand {
    public function __construct(
        public string $name,
        public string $description,
        public int $sortOrder,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromRequest(array $data) : self {
        return new self(
            name: $data['name']
            |>trim(...)
            |>mb_strtolower(...)
            |>mb_ucfirst(...),
            description: trim($data['description'] ?? ''),
            sortOrder:(int) filter_var($data['sortOrder'], FILTER_VALIDATE_INT),
        );
    }
}
