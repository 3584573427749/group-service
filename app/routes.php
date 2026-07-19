<?php

declare(strict_types=1);

use App\Http\Actions\GroupLevel\CreateGroupLevelAction;
use App\Http\Actions\GroupLevel\GetAllGroupLevelsAction;
use Slim\App;

return function (App $app) : void {

    $app->post('/group-levels', CreateGroupLevelAction::class);
    $app->get('/group-levels', GetAllGroupLevelsAction::class);
};
