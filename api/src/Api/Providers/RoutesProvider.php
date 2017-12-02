<?php

namespace Api\Providers;

use Api\Controllers\NotesController;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

class RoutesProvider implements ServiceProviderInterface
{
    public function boot(Container $app)
    {
        return null;
    }

    public function register(Container $app)
    {
        $app['notes.controller'] = function () use ($app) {
            return new NotesController($app['notes.service']);
        };

        $controllers_factory = $app['controllers_factory'];

        $controllers_factory->get('/notes', 'notes.controller:getAll');
        $controllers_factory->get('/notes/{id}', 'notes.controller:getOne');
        $controllers_factory->post('/notes', 'notes.controller:save');
        $controllers_factory->put('/notes/{id}', 'notes.controller:update');
        $controllers_factory->delete('/notes/{id}', 'notes.controller:delete');

        $app->mount('/', $controllers_factory);
    }
}

