<?php

declare(strict_types=1);

namespace App\Application\Validators;

class UpdateGroupLevelSortOrderValidator {
    /**
     * @param array<string, mixed> $data
     * @return string[]
     */
    public static function validate(array $data) : array {
        $errors = [];

        foreach ($data as $itm) {
            if (!isset($itm['id'])) {
                $errors['id'] = 'Id saknas i anrop';
            }

            if (!isset($itm['sortOrder'])) {
                $errors['sortOrder'] = 'SortOrder behöver finnas.';
            } else {
                $sortOrder = filter_var($itm['sortOrder'], FILTER_VALIDATE_INT);
                if ($sortOrder === false) {
                    $errors['sortOrder'] = 'SortOrder måste vara ett heltal.';
                } elseif ($sortOrder < 0) {
                    $errors['sortOrder'] = 'SortOrder måste vara ett positivt heltal.';
                }
            }
        }

        return $errors;
    }
}
