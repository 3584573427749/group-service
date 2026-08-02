<?php

declare(strict_types=1);

namespace App\Application\Commands\GroupLeader;

class GroupLeaderCommand {
    private function __construct(public string $groupId, public string $userId, public string $role) {

    }

    /**
     * @param array<string, string> $data
     */
    public static function fromRequest(array $data) : self {
        // Normalisera
        $groupId = $data['groupId'];
        $userId = $data['userId'];
        $role = $data['role'];

        return new self($groupId, $userId, $role);
    }
}
