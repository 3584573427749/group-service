<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Enums;

use App\Domain\Enums\Role;
use PHPUnit\Framework\TestCase;

final class RoleTest extends TestCase {
    public function testCasesExist() : void {
        $cases = Role::cases();

        self::assertCount(3, $cases);

        self::assertContains(Role::LEADER, $cases);
        self::assertContains(Role::ASSISTANT, $cases);
        self::assertContains(Role::EDUCATOR, $cases);
    }

    public function testValuesAreCorrect() : void {
        self::assertSame(
            'Ledare',
            Role::LEADER->value,
        );

        self::assertSame(
            'Assistent',
            Role::ASSISTANT->value,
        );

        self::assertSame(
            'Utbildare',
            Role::EDUCATOR->value,
        );
    }

    public function testFromReturnsCorrectEnum() : void {
        self::assertSame(
            Role::LEADER,
            Role::from('Ledare'),
        );

        self::assertSame(
            Role::ASSISTANT,
            Role::from('Assistent'),
        );

        self::assertSame(
            Role::EDUCATOR,
            Role::from('Utbildare'),
        );
    }

    public function testTryFromReturnsCorrectEnum() : void {
        self::assertSame(
            Role::LEADER,
            Role::tryFrom('Ledare'),
        );

        /** @phpstan-ignore-next-line */
        self::assertNull(Role::tryFrom('Banan'));
    }

    public function testFromThrowsExceptionForInvalidValue() : void {
        self::expectException(\ValueError::class);

        Role::from('Banan');
    }
}
