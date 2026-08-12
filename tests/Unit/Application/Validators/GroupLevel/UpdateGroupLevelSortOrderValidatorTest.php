<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\UpdateGroupLevelSortOrderValidator;
use PHPUnit\Framework\TestCase;

final class UpdateGroupLevelSortOrderValidatorTest extends TestCase {
    public function testValidDataReturnsNoErrors() : void {
        $data = [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => 1,
            ],
            [
                'id' => '660e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => 2,
            ],
        ];

        $errors = UpdateGroupLevelSortOrderValidator::validate(
            $data,
        );

        self::assertSame([], $errors);
    }

    public function testEmptyArrayReturnsError() : void {
        $errors = UpdateGroupLevelSortOrderValidator::validate(
            [],
        );

        self::assertArrayHasKey('data', $errors);

        self::assertSame(
            'Ogiltig dataformat',
            $errors['data'],
        );
    }

    public function testMissingId() : void {
        $data = [
            [
                'sortOrder' => 1,
            ],
        ];

        $errors = UpdateGroupLevelSortOrderValidator::validate(
            $data,
        );

        self::assertArrayHasKey('id', $errors);

        self::assertSame(
            'Id saknas i anrop',
            $errors['id'],
        );
    }

    public function testMissingSortOrder() : void {
        $data = [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
            ],
        ];

        $errors = UpdateGroupLevelSortOrderValidator::validate(
            $data,
        );

        self::assertArrayHasKey('sortOrder', $errors);

        self::assertSame(
            'SortOrder behöver finnas.',
            $errors['sortOrder'],
        );
    }

    public function testSortOrderMustBeInteger() : void {
        $data = [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => 'abc',
            ],
        ];

        $errors = UpdateGroupLevelSortOrderValidator::validate(
            $data,
        );

        self::assertArrayHasKey('sortOrder', $errors);

        self::assertSame(
            'SortOrder måste vara ett heltal.',
            $errors['sortOrder'],
        );
    }

    public function testSortOrderMustBePositiveInteger() : void {
        $data = [
            [
                'id' => '550e8400-e29b-41d4-a716-446655440000',
                'sortOrder' => -1,
            ],
        ];

        $errors = UpdateGroupLevelSortOrderValidator::validate(
            $data,
        );

        self::assertArrayHasKey('sortOrder', $errors);

        self::assertSame(
            'SortOrder måste vara ett positivt heltal.',
            $errors['sortOrder'],
        );
    }

    public function testMultipleErrors() : void {
        $data = [
            [
                'sortOrder' => 'abc',
            ],
        ];

        $errors = UpdateGroupLevelSortOrderValidator::validate(
            $data,
        );

        self::assertCount(2, $errors);

        self::assertSame(
            'Id saknas i anrop',
            $errors['id'],
        );

        self::assertSame(
            'SortOrder måste vara ett heltal.',
            $errors['sortOrder'],
        );
    }
}
