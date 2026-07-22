<?php

declare(strict_types=1);

namespace App\Application\Validators;

class GroupValidator {
    /**
     * @param array<string, mixed> $data
     * @return array<string, string>
     */
    public static function validateCreate(array $data) : array {
        $errors = [];

        self::validateName($data, $errors);
        self::validateVenue($data, $errors);
        self::validateActive($data, $errors);
        self::validateCompetitive($data, $errors);

        return $errors;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, string>
     */
    public static function validateUpdate(array $data) : array {
        $errors = [];
        self::validateId($data, $errors);
        self::validateGroupLevelId($data, $errors);
        self::validateName($data, $errors);
        self::validateVenue($data, $errors);
        self::validateActive($data, $errors);
        self::validateCompetitive($data, $errors);

        return $errors;
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    private static function validateId(array $data, array &$errors) : void {
        if (!isset($data['id'])) {
            $errors['id'] = 'Id saknas i anrop';
        } elseif ($data['id'] !== $data['groupId']) {
            $errors['id'] = 'Id matchar inte anropet';
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    private static function validateGroupLevelId(array $data, array &$errors) : void {
        if (!isset($data['groupLevelId']) || empty($data['groupLevelId'])) {
            $errors['groupLevelId'] = 'Gruppnivå-ID är obligatoriskt';
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    private static function validateName(array $data, array &$errors) : void {
        if (!isset($data['name']) || empty($data['name'])) {
            $errors['name'] = 'Namn är obligatoriskt';
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    private static function validateVenue(array $data, array &$errors) : void {
        if (!isset($data['venue']) || empty($data['venue'])) {
            $errors['venue'] = 'Plats är obligatoriskt';
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    private static function validateActive(array $data, array &$errors) : void {
        if (!isset($data['active'])) {
            $errors['active'] = 'Aktiv är obligatoriskt';
        } else {
            $active = filter_var($data['active'], FILTER_VALIDATE_INT);
            if ($active === false || ($active > 1 || $active < 0)) {
                $errors['active'] = 'Aktiv måste vara 1 eller 0';
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $errors
     */
    private static function validateCompetitive(array $data, array &$errors) : void {
        if (!isset($data['competitive'])) {
            $errors['competitive'] = 'Tävlingsgrupp är obligatoriskt';
        } else {
            $competitive = filter_var($data['competitive'], FILTER_VALIDATE_INT);
            if ($competitive === false || ($competitive > 1 || $competitive < 0)) {
                $errors['competitive'] = 'Tävlingsgrupp måste vara 1 eller 0';
            }
        }
    }
}
