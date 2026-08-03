<?php

declare(strict_types=1);

namespace App\Domain\Enums;

enum Role : string {
    case LEADER = 'Ledare';
    case ASSISTANT = 'Assistent';
    case EDUCATOR = 'Utbildare';
}
