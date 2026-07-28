<?php

declare(strict_types=1);

namespace App\Application\Commands\User;

use App\Domain\ValueObjects\DateTimeValue;
use App\Domain\ValueObjects\UserId;

class UpsertUserCommand {
    public function __construct(
        public UserId $id,
        public string $firstName,
        public string $lastName,
        public int $active,
        public DateTimeValue $createdAt,
        public ?DateTimeValue $updatedAt = null,
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromRequest(array $data) : self {
        return new self(
            new UserId($data['id']),
            trim($data['firstName']),
            trim($data['lastName']),
            (int)filter_var($data['active'], FILTER_VALIDATE_INT),
            new DateTimeValue(date('Y-m-d H:i:s')),
            null,
        );
    }
}
