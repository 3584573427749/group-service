<?php

declare(strict_types=1);

namespace App\Application\Commands\GroupLevel;

use App\Domain\DataTransportObjects\GroupLevelSortOrderDTO;
use App\Domain\ValueObjects\GroupLevelId;

class UpdateGroupLevelSortOrderCommand {
    /**
     * @param array<GroupLevelSortOrderDTO> $command
     */
    public function __construct(
        public array $command,
    ) {
    }

    /**
     * @param array<array<string, mixed>> $data
     */
    public static function fromRequest(array $data) : self {
        $items = [];
        foreach ($data as $item) {
            $items[] = new GroupLevelSortOrderDTO(
                id: new GroupLevelId($item['id']),
                sortOrder: (int) filter_var($item['sortOrder'], FILTER_VALIDATE_INT),
            );
        }
        return new self(
            command: $items,
        );
    }
}
