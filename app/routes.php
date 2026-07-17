<?php

declare(strict_types=1);

use Slim\App;

return function (App $app) : void {

    $app->get('/', function ($request, $response, $args) {
        $response->getBody()->write('Hello World!');
        return $response;
    });
};
