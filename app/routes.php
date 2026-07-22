<?php

declare(strict_types=1);

use App\Http\Actions\Group\CreateGroupAction;
use App\Http\Actions\Group\DeleteGroupAction;
use App\Http\Actions\Group\GetAllGroupsAction;
use App\Http\Actions\Group\GetGroupAction;
use App\Http\Actions\Group\UpdateGroupAction;
use App\Http\Actions\GroupLevel\CreateGroupLevelAction;
use App\Http\Actions\GroupLevel\DeleteGroupLevelAction;
use App\Http\Actions\GroupLevel\GetAllGroupLevelsAction;
use App\Http\Actions\GroupLevel\GetGroupLevelAction;
use App\Http\Actions\GroupLevel\UpdateGroupLevelAction;
use Slim\App;

return function (App $app) : void {

    $app->post('/group-levels', CreateGroupLevelAction::class);
    $app->get('/group-levels', GetAllGroupLevelsAction::class);
    $app->get('/group-levels/{id}', GetGroupLevelAction::class);
    $app->put('/group-levels/{id}', UpdateGroupLevelAction::class);
    $app->delete('/group-levels/{id}', DeleteGroupLevelAction::class);

    $app->post('/group', CreateGroupAction::class);
    $app->get('/group', GetAllGroupsAction::class);
    $app->get('/group/{id}', GetGroupAction::class);
    $app->put('/group/{id}', UpdateGroupAction::class);
    $app->delete('/group/{id}', DeleteGroupAction::class);
};
