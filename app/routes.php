<?php
declare(strict_types=1);

use App\Application\Actions\Task\CompleteTaskAction;
use App\Application\Actions\Task\CreateTaskAction;
use App\Application\Actions\Task\DeleteTaskAction;
use App\Application\Actions\Task\ListTasksAction;
use App\Application\Actions\Task\ViewTaskAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('TODO app!');
        return $response;
    });

    $app->group('/tasks', function (Group $group) {
        $group->get('', ListTasksAction::class);
        $group->post('', CreateTaskAction::class);
        $group->get('/{id}', ViewTaskAction::class);
        $group->delete('/{id}', DeleteTaskAction::class);
        $group->post('/{id}/complete', CompleteTaskAction::class);
    });
};
