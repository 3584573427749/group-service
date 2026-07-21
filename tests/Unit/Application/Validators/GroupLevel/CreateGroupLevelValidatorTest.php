<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\CreateGroupLevelValidator;
use PHPUnit\Framework\TestCase;

final class CreateGroupLevelValidatorTest extends TestCase {
    public function testValidDataReturnsNoErrors() : void {
        $data = [
            'name' => 'Baddaren',
            'sortOrder' => 1,
        ];

        $errors = CreateGroupLevelValidator::validate($data);

        self::assertSame([], $errors);
    }

    public function testMissingName() : void {
        $data = [
            'sortOrder' => 1,
        ];

        $errors = CreateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('name', $errors);
        self::assertSame(
            'Name is required.',
            $errors['name'],
        );
    }

    public function testNameTooShort() : void {
        $data = [
            'name' => 'A',
            'sortOrder' => 1,
        ];

        $errors = CreateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('name', $errors);
        self::assertSame(
            'Name must be at least 2 characters.',
            $errors['name'],
        );
    }

    public function testMissingSortOrder() : void {
        $data = [
            'name' => 'Baddaren',
        ];

        $errors = CreateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('sortOrder', $errors);
        self::assertSame(
            'SortOrder behöver finnas.',
            $errors['sortOrder'],
        );
    }

    public function testSortOrderMustBeInteger() : void {
        $data = [
            'name' => 'Baddaren',
            'sortOrder' => 'abc',
        ];

        $errors = CreateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('sortOrder', $errors);
        self::assertSame(
            'SortOrder måste vara ett heltal.',
            $errors['sortOrder'],
        );
    }

    public function testSortOrderMustBePositiveInteger() : void {
        $data = [
            'name' => 'Baddaren',
            'sortOrder' => -1,
        ];

        $errors = CreateGroupLevelValidator::validate($data);

        self::assertArrayHasKey('sortOrder', $errors);
        self::assertSame(
            'SortOrder måste vara ett positivt heltal.',
            $errors['sortOrder'],
        );
    }

    public function testMultipleErrors() : void {
        $data = [
            'name' => 'A',
            'sortOrder' => 'abc',
        ];

        $errors = CreateGroupLevelValidator::validate($data);

        self::assertCount(2, $errors);

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
