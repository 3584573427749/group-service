<?php

declare(strict_types=1);

use App\Domain\Repositories\GroupLevelRepository;
use App\Infrastructure\Database\DbalGroupLevelRepository;

use function DI\autowire;

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Repository-mappningar
    $containerBuilder->addDefinitions([
        GroupLevelRepository::class => autowire(DbalGroupLevelRepository::class),
    ]);
};
