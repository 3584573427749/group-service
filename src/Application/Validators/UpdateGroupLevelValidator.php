<?php

declare(strict_types=1);

namespace App\Application\Validators;

class UpdateGroupLevelValidator {
    /**
     * @param array<string, mixed> $data
     * @return string[]
     */
    public static function validate(array $data) : array {
        // $data['groupLevelId'] sätts av Action från route-parametern
        // och används för att säkerställa att payload-id
        // matchar det id som efterfrågas i URL:en.
        $errors = [];

        if (!isset($data['id'])) {
            $errors['id'] = 'Id saknas i anrop';
        } elseif ($data['id'] !== $data['groupLevelId']) {
            $errors['id'] = 'Id matchar inte anropet';
        }

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
