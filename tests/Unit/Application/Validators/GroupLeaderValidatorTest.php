<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\GroupLeaderValidator;
use App\Domain\Enums\Role;
use PHPUnit\Framework\TestCase;

final class GroupLeaderValidatorTest extends TestCase {
    public function testValidDataReturnsNoErrors() : void {
        $data = [
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => Role::LEADER->value,
        ];

        $errors = GroupLeaderValidator::validate($data);

        self::assertSame([], $errors);
    }

    public function testMissingGroupId() : void {
        $data = [
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => Role::LEADER->value,
        ];

        $errors = GroupLeaderValidator::validate($data);

        self::assertArrayHasKey('groupId', $errors);
        self::assertSame('groupId saknas i anrop', $errors['groupId']);
    }

    public function testMissingUserId() : void {
        $data = [
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'role' => Role::LEADER->value,
        ];

        $errors = GroupLeaderValidator::validate($data);

        self::assertArrayHasKey('userId', $errors);
        self::assertSame('userId saknas i anrop', $errors['userId']);
    }

    public function testMissingRole() : void {
        $data = [
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
        ];

        $errors = GroupLeaderValidator::validate($data);

        self::assertArrayHasKey('role', $errors);
        self::assertSame('Role är obligatoriskt', $errors['role']);
    }

    public function testEmptyRole() : void {
        $data = [
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => '',
        ];

        $errors = GroupLeaderValidator::validate($data);

        self::assertArrayHasKey('role', $errors);
        self::assertSame('Role är obligatoriskt', $errors['role']);
    }

    public function testInvalidRole() : void {
        $data = [
            'groupId' => '550e8400-e29b-41d4-a716-446655440000',
            'userId' => '660e8400-e29b-41d4-a716-446655440000',
            'role' => 'Banan',
        ];

        $errors = GroupLeaderValidator::validate($data);

        self::assertArrayHasKey('role', $errors);
        self::assertSame('Ogiltig roll', $errors['role']);
    }

    public function testMultipleErrors() : void {
        $data = [];

        $errors = GroupLeaderValidator::validate($data);

        self::assertCount(3, $errors);

        self::assertSame('groupId saknas i anrop', $errors['groupId']);

        self::assertSame('userId saknas i anrop', $errors['userId']);

        self::assertSame('Role är obligatoriskt', $errors['role']);
    }
}
