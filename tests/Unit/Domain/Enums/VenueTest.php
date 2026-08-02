<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Enums;

use App\Domain\Enums\Venue;
use PHPUnit\Framework\TestCase;

final class VenueTest extends TestCase {
    public function testCasesExist() : void {
        $cases = Venue::cases();

        self::assertCount(2, $cases);

        self::assertContains(Venue::MARIEBAD, $cases);
        self::assertContains(Venue::ALANDS_IDROTTCENTER, $cases);
    }

    public function testValuesAreCorrect() : void {
        self::assertSame(
            'Mariebad',
            Venue::MARIEBAD->value,
        );

        self::assertSame(
            'Ålands Idrottscenter',
            Venue::ALANDS_IDROTTCENTER->value,
        );
    }

    public function testFromReturnsCorrectEnum() : void {
        self::assertSame(
            Venue::MARIEBAD,
            Venue::from('Mariebad'),
        );

        self::assertSame(
            Venue::ALANDS_IDROTTCENTER,
            Venue::from('Ålands Idrottscenter'),
        );
    }

    public function testFromThrowsExceptionForInvalidValue() : void {
        self::expectException(\ValueError::class);

        Venue::from('Banan');
    }

    public function testTryFromReturnsCorrectEnum() : void {
        self::assertSame(
            Venue::MARIEBAD,
            Venue::tryFrom('Mariebad'),
        );

        /** @phpstan-ignore-next-line */
        self::assertNull(Venue::tryFrom('Banan'), );
    }
}
