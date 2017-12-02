<?php

date_default_timezone_set('Europe/London');

require_once __DIR__ . '/../vendor/autoload.php';

use Api\RoutesLoader;
use Api\Providers\ServicesProvider;
use Api\Providers\RoutesProvider;

use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Carbon\Carbon;
use Euskadi31\Silex\Provider\CorsServiceProvider;
use GeckoPackages\Silex\Services\Config\ConfigServiceProvider;

$app = new Silex\Application();

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : []);
    }
});

$app->register(
        new ConfigServiceProvider(),
        [
            'config.dir' => __DIR__.'/../resources/config',
            'config.format' => '%key%.%env%.json',
            'config.env' => getenv('APP_ENV')
        ]
);

$app->register(new CorsServiceProvider());

$app->register(new ServiceControllerServiceProvider());

$app->register(
    new DoctrineServiceProvider(), 
    [
        'db.options' => $app['config']->get('database')
    ]
);

$app->register(
    new HttpCacheServiceProvider(), 
    [
        'http_cache.cache_dir' => '../var/cache'
    ]
);

$app->register(
    new MonologServiceProvider(), 
    [
        'monolog.logfile' => 
        sprintf('../var/logs/%s.log', Carbon::now('Europe/London')->format('Y-m-d')),
        'monolog.level' => Monolog\Logger::ERROR,
        'monolog.name' => 'application'
    ]
);

$app->register(new ServicesProvider(), []);

$app->register(new RoutesProvider(), []);

$app->error(function (\Exception $e, $code) use ($app) {
    $app['monolog']->addError($e->getMessage());
    $app['monolog']->addError($e->getTraceAsString());
    return new JsonResponse(array('status' => $code, 'message' => $e->getMessage(), 'stacktrace' => $e->getTraceAsString()));
});

$app['http_cache']->run();
