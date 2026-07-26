<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\GroupValidator;
use PHPUnit\Framework\TestCase;

final class GroupValidatorTest extends TestCase {
    public function testValidateCreateReturnsNoErrorsForValidData() : void {
        $data = [
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Tävlingsgrupp A',
            'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 1,
        ];

        $errors = GroupValidator::validateCreate($data);

        self::assertSame([], $errors);
    }

    public function testValidateUpdateReturnsNoErrorsForValidData() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '660e8400-e29b-41d4-a716-446655440000',
            'name' => 'Tävlingsgrupp A',
            'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 0,
        ];

        $errors = GroupValidator::validateUpdate($data);

        self::assertSame([], $errors);
    }

    public function testValidateUpdateFailsWhenIdIsMissing() : void {
        $data = [
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '660e8400-e29b-41d4-a716-446655440000',
            'name' => 'Grupp',
            'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 0,
        ];

        $errors = GroupValidator::validateUpdate($data);

        self::assertSame(
            'Id saknas i anrop',
            $errors['id'],
        );
    }

    public function testValidateUpdateFailsWhenIdsDoNotMatch() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupId' => '660e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '770e8400-e29b-41d4-a716-446655440000',
            'name' => 'Grupp',
            'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 0,
        ];

        $errors = GroupValidator::validateUpdate($data);

        self::assertSame(
            'Id matchar inte anropet',
            $errors['id'],
        );
    }

    public function testGroupLevelIdIsRequired() : void {
        $errors = GroupValidator::validateCreate([
            'name' => 'Grupp',
            'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 0,
        ]);

        self::assertSame(
            'Gruppnivå-ID är obligatoriskt',
            $errors['groupLevelId'],
        );
    }

    public function testNameIsRequired() : void {
        $errors = GroupValidator::validateCreate([
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 0,
        ]);

        self::assertSame(
            'Namn är obligatoriskt',
            $errors['name'],
        );
    }

    public function testVenueIsRequired() : void {
        $errors = GroupValidator::validateCreate([
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Grupp',
            'active' => 1,
            'competitive' => 0,
        ]);

        self::assertSame(
            'Plats är obligatoriskt',
            $errors['venue'],
        );
    }

    public function testActiveIsRequired() : void {
        $errors = GroupValidator::validateCreate([
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Grupp',
            'venue' => 'Mariebad',
            'competitive' => 0,
        ]);

        self::assertSame(
            'Aktiv är obligatoriskt',
            $errors['active'],
        );
    }

    public function testActiveMustBeZeroOrOne() : void {
        $errors = GroupValidator::validateCreate([
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Grupp',
            'venue' => 'Mariebad',
            'active' => 2,
            'competitive' => 0,
        ]);

        self::assertSame(
            'Aktiv måste vara 1 eller 0',
            $errors['active'],
        );
    }

    public function testCompetitiveIsRequired() : void {
        $errors = GroupValidator::validateCreate([
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Grupp',
            'venue' => 'Mariebad',
            'active' => 1,
        ]);

        self::assertSame(
            'Tävlingsgrupp är obligatoriskt',
            $errors['competitive'],
        );
    }

    public function testCompetitiveMustBeZeroOrOne() : void {
        $errors = GroupValidator::validateCreate([
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Grupp',
            'venue' => 'Mariebad',
            'active' => 1,
            'competitive' => 2,
        ]);

        self::assertSame(
            'Tävlingsgrupp måste vara 1 eller 0',
            $errors['competitive'],
        );
    }

    public function testReturnsMultipleErrors() : void {
        $errors = GroupValidator::validateCreate([]);

        self::assertCount(5, $errors);

        self::assertArrayHasKey('groupLevelId', $errors);
        self::assertArrayHasKey('name', $errors);
        self::assertArrayHasKey('venue', $errors);
        self::assertArrayHasKey('active', $errors);
        self::assertArrayHasKey('competitive', $errors);
    }
}
