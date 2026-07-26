<?php

declare(strict_types=1);

use App\Domain\Repositories\GroupLevelRepository;
use App\Domain\Repositories\GroupRepository;
use App\Infrastructure\Database\DbalGroupLevelRepository;
use App\Infrastructure\Database\DbalGroupRepository;

use function DI\autowire;

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Repository-mappningar
    $containerBuilder->addDefinitions([
        GroupLevelRepository::class => autowire(DbalGroupLevelRepository::class),
        GroupRepository::class => autowire(DbalGroupRepository::class),
    ]);
};
