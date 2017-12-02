<?php

namespace Api\Providers;

use Api\Services\NotesService;
use Pimple\ServiceProviderInterface;
use Pimple\Container;

class ServicesProvider implements ServiceProviderInterface
{
    public function boot(Container $app)
    {
        return null;
    }

    public function register(Container $app)
    {
        $app['notes.service'] = function () use ($app) {
            return new NotesService($app['db']);
        };
    }
}

