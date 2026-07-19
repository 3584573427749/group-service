<?php

declare(strict_types=1);

namespace App\Application\Validators;

use Ramsey\Uuid\Uuid;

class UpdateGroupLevelValidator {
    /**
     * @param array<string, mixed> $data
     * @return string[]
     */
    public static function validate(array $data) : array {
        $errors = [];

        if (!isset($data['id'])) {
            $errors['id'] = 'Id saknas i anrop';
        } elseif ($data['id'] !== $data['groupLevelId']) {
            $errors['id'] = 'Id matchar inte anropet';
        } elseif (!Uuid::isValid($data['id'])) {
            $errors['id'] = 'Id är ogiltigt formaterat';
        }

        if (!isset($data['name'])) {
            $errors['name'] = 'Name is required.';
        } elseif (mb_strlen($data['name']) < 2) {
            $errors['name'] = 'Name must be at least 2 characters.';
        }

        if (!isset($data['sortOrder'])) {
            $errors['sortOrder'] = 'SortOrder behöver finnas.';
        } elseif (filter_var($data['sortOrder'], FILTER_VALIDATE_INT) === false) {
            $errors['sortOrder'] = 'SortOrder måste vara ett heltal.';
        } elseif (filter_var($data['sortOrder'], FILTER_VALIDATE_INT) < 0) {
            $errors['sortOrder'] = 'SortOrder måste vara ett positivt heltal.';
        }

        return $errors;
    }
}
