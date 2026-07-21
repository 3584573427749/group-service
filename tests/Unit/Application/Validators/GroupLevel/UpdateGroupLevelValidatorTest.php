<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\UpdateGroupLevelValidator;
use PHPUnit\Framework\TestCase;

final class UpdateGroupLevelValidatorTest extends TestCase {
    public function testValidDataReturnsNoErrors() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'sortOrder' => 1,
        ];

        $errors = UpdateGroupLevelValidator::validate($data);

        self::assertSame([], $errors);
    }

    public function testMissingId() : void {
        $data = [
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'sortOrder' => 1,
        ];

        $errors = UpdateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('id', $errors);
        self::assertSame(
            'Id saknas i anrop',
            $errors['id'],
        );
    }

    public function testIdDoesNotMatchRequestId() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '660e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'sortOrder' => 1,
        ];

        $errors = UpdateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('id', $errors);
        self::assertSame(
            'Id matchar inte anropet',
            $errors['id'],
        );
    }

    public function testMissingName() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'sortOrder' => 1,
        ];

        $errors = UpdateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('name', $errors);
        self::assertSame(
            'Name is required.',
            $errors['name'],
        );
    }

    public function testNameTooShort() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'A',
            'sortOrder' => 1,
        ];

        $errors = UpdateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('name', $errors);
        self::assertSame(
            'Name must be at least 2 characters.',
            $errors['name'],
        );
    }

    public function testMissingSortOrder() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
        ];

        $errors = UpdateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('sortOrder', $errors);
        self::assertSame(
            'SortOrder behöver finnas.',
            $errors['sortOrder'],
        );
    }

    public function testSortOrderMustBeInteger() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'sortOrder' => 'abc',
        ];

        $errors = UpdateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('sortOrder', $errors);
        self::assertSame(
            'SortOrder måste vara ett heltal.',
            $errors['sortOrder'],
        );
    }

    public function testSortOrderMustBePositiveInteger() : void {
        $data = [
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'groupLevelId' => '550e8400-e29b-41d4-a716-446655440000',
            'name' => 'Baddaren',
            'sortOrder' => -1,
        ];

        $errors = UpdateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('sortOrder', $errors);
        self::assertSame(
            'SortOrder måste vara ett positivt heltal.',
            $errors['sortOrder'],
        );
    }

    public function testMultipleErrors() : void {
        $data = [
            'id' => '12',
            'groupLevelId' => '34',
            'name' => 'A',
            'sortOrder' => 'abc',
        ];

        $errors = UpdateGroupLevelValidator::validate($data);

        self::assertCount(3, $errors);

        self::assertSame(
            'Id matchar inte anropet',
            $errors['id'],
        );

        self::assertSame(
            'Name must be at least 2 characters.',
            $errors['name'],
        );

        self::assertSame(
            'SortOrder måste vara ett heltal.',
            $errors['sortOrder'],
        );
    }
}
