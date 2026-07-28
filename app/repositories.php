<?php

declare(strict_types=1);

use App\Domain\Repositories\GroupLevelRepository;
use App\Domain\Repositories\GroupRepository;
use App\Domain\Repositories\UserRepository;
use App\Infrastructure\Database\DbalGroupLevelRepository;
use App\Infrastructure\Database\DbalGroupRepository;
use App\Infrastructure\Database\DbalUserRepository;

use function DI\autowire;

use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Repository-mappningar
    $containerBuilder->addDefinitions([
        GroupLevelRepository::class => autowire(DbalGroupLevelRepository::class),
        GroupRepository::class => autowire(DbalGroupRepository::class),
        UserRepository::class => autowire(DbalUserRepository::class),
    ]);
};
