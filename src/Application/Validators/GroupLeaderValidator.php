<?php

declare(strict_types=1);

namespace App\Application\Validators;

use App\Domain\Enums\Role;

class GroupLeaderValidator {
    /**
     * @param array<string, mixed> $data
     * @return array<string, string>
     */
    public static function validate(array $data) : array {
        $errors = [];

        self::validateGroupId($data, $errors);
        self::validateUserId($data, $errors);
        self::validateRole($data, $errors);

        return $errors;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    private static function validateGroupId(array $data, array &$errors) : void {
        if (!isset($data['groupId'])) {
            $errors['groupId'] = 'groupId saknas i anrop';
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    private static function validateUserId(array $data, array &$errors) : void {
        if (!isset($data['userId'])) {
            $errors['userId'] = 'userId saknas i anrop';
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    private static function validateRole(array $data, array &$errors) : void {
        if (!isset($data['role']) || empty($data['role'])) {
            $errors['role'] = 'Role är obligatoriskt';
        } else {
            try {
                Role::from($data['role']);
            } catch (\ValueError) {
                $errors['role'] = 'Ogiltig roll';
            }
        }
    }
}
