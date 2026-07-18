<?php

declare(strict_types=1);

use App\Http\Actions\GroupLevel\CreateGroupLevelAction;
use Slim\App;

return function (App $app) : void {

    $app->post('/group-levels', CreateGroupLevelAction::class);
};
