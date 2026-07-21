<?php

declare(strict_types=1);

namespace App\Application\Validators;

class CreateGroupLevelValidator {
    /**
     * @param array<string, mixed> $data
     * @return string[]
     */
    public static function validate(array $data) : array {
        $errors = [];

        if (!isset($data['name'])) {
            $errors['name'] = 'Name is required.';
        } elseif (mb_strlen($data['name']) < 2) {
            $errors['name'] = 'Name must be at least 2 characters.';
        }

        if (!isset($data['sortOrder'])) {
            $errors['sortOrder'] = 'SortOrder behöver finnas.';
        } else {
            $sortOrder = filter_var($data['sortOrder'], FILTER_VALIDATE_INT);
            if ($sortOrder === false) {
                $errors['sortOrder'] = 'SortOrder måste vara ett heltal.';
            } elseif ($sortOrder < 0) {
                $errors['sortOrder'] = 'SortOrder måste vara ett positivt heltal.';
            }
        }

        return $errors;
    }
}
