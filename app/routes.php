<?php

declare(strict_types=1);

use App\Http\Actions\Group\CreateGroupAction;
use App\Http\Actions\Group\DeleteGroupAction;
use App\Http\Actions\Group\GetAllGroupsAction;
use App\Http\Actions\Group\GetGroupAction;
use App\Http\Actions\Group\UpdateGroupAction;
use App\Http\Actions\GroupLeader\DeleteGroupLeaderAction;
use App\Http\Actions\GroupLeader\DeleteGroupLeadersAction;
use App\Http\Actions\GroupLeader\DeleteLeaderGroupsAction;
use App\Http\Actions\GroupLeader\GetGroupLeadersAction;
use App\Http\Actions\GroupLeader\GetLeaderGroupsAction;
use App\Http\Actions\GroupLeader\UpsertGroupLeaderAction;
use App\Http\Actions\GroupLevel\CreateGroupLevelAction;
use App\Http\Actions\GroupLevel\DeleteGroupLevelAction;
use App\Http\Actions\GroupLevel\GetAllGroupLevelsAction;
use App\Http\Actions\GroupLevel\GetGroupLevelAction;
use App\Http\Actions\GroupLevel\UpdateGroupLevelAction;
use App\Http\Actions\User\DeleteUserAction;
use App\Http\Actions\User\GetAllUsersAction;
use App\Http\Actions\User\GetUserAction;
use App\Http\Actions\User\UpsertUserAction;
use Slim\App;

return function (App $app) : void {

    $app->post('/group-levels', CreateGroupLevelAction::class);
    $app->get('/group-levels', GetAllGroupLevelsAction::class);
    $app->get('/group-levels/{id}', GetGroupLevelAction::class);
    $app->put('/group-levels/{id}', UpdateGroupLevelAction::class);
    $app->delete('/group-levels/{id}', DeleteGroupLevelAction::class);

    $app->post('/groups', CreateGroupAction::class);
    $app->get('/groups', GetAllGroupsAction::class);
    $app->get('/groups/{id}', GetGroupAction::class);
    $app->put('/groups/{id}', UpdateGroupAction::class);
    $app->delete('/groups/{id}', DeleteGroupAction::class);

    $app->post('/users', UpsertUserAction::class);
    $app->get('/users', GetAllUsersAction::class);
    $app->get('/users/{id}', GetUserAction::class);
    $app->delete('/users/{id}', DeleteUserAction::class);

    $app->post('/groups/{id}/users', UpsertGroupLeaderAction::class);
    $app->get('/groups/{id}/users', GetGroupLeadersAction::class);
    $app->get('/users/{id}/groups', GetLeaderGroupsAction::class);
    $app->delete('/groups/{id}/users/{userId}', DeleteGroupLeaderAction::class);
    $app->delete('/groups/{id}/users', DeleteGroupLeadersAction::class);
    $app->delete('/user/{id}/groups', DeleteLeaderGroupsAction::class);

};
