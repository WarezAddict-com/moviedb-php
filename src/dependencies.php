<?php

// Use Libs
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Respect\Validation\Validator as Val;
use \Cocur\Slugify\Bridge\Twig\SlugifyExtension;
use \Cocur\Slugify\Slugify;

// Slim Framework Container
$container = $app->getContainer();

// Debug Mode
if (getenv('APP_DEBUG') == 'yes') {

    $dmode = true;

    // Whoops
    $whoopsGuard = new \Zeuxisoo\Whoops\Provider\Slim\WhoopsGuard();
    $whoopsGuard->setApp($app);
    $whoopsGuard->setRequest($container['request']);
    $whoopsGuard->setHandlers([]);
    $whoopsGuard->install();

} else {
    $dmode = false;
}

// SQLite Database
require_once APP_ROOT . '/src/database.php';
$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

// Logger
$container['logger'] = function ($container) {
    $logPath = APP_ROOT.'/logs/APP_'.date('m-d-Y').'.log';
    $logger = new \Monolog\Logger('Turbo');
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($logPath, \Monolog\Logger::DEBUG));
    return $logger;
};

// CSRF Protection
$container['csrf'] = function ($container) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setPersistentTokenMode(true);
    return $guard;
};

// Auth
$container['auth'] = function ($container) {
    return new \Turbo\Auth\Auth;
};

// Flash Messages
$container['flash'] = function ($container) {
    return new \Slim\Flash\Messages();
};

$container['tmdb'] = function ($container) {

    // API Key
    $apiKey = getenv('TMDB_API_KEY');
    $token  = new \Tmdb\ApiToken($apiKey);

    // Client
    $client = new \Tmdb\Client($token, [
        'secure' => false,
        'cache' => [
            'path' => APP_ROOT . '/cache',
        ],
        'log' => [
            'enabled' => true,
            'path' => APP_ROOT . '/logs/tmdb_'.date('m-d-Y').'.log',
        ]
    ]);

    $configRepository = new \Tmdb\Repository\ConfigurationRepository($client);
    $config = $configRepository->load();

    $imageHelper = new \Tmdb\Helper\ImageHelper($config);

    $langPlugin = new \Tmdb\HttpClient\Plugin\LanguageFilterPlugin('en-US');
    $client->getHttpClient()->addSubscriber($langPlugin);

    $adultPlugin = new \Tmdb\HttpClient\Plugin\AdultFilterPlugin(false);
    $client->getHttpClient()->addSubscriber($adultPlugin);

    return $client;

    // MovieRepo
    // $movieRepo = new \Tmdb\Repository\MovieRepository($client);

    // Search
    // $searchRepo = new \Tmdb\Repository\SearchRepository($client);
};

// View (Twig)
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(
        APP_ROOT . '/views',[
            'debug' => true,
            'cache' => false,
            'auto_reload' => true,
            'autoescape' => false,
        ]
    );

    // Slim View
    $view->addExtension(new \Slim\Views\TwigExtension($container->router, $container->request->getUri()));

    // Enable Debug Mode
    $view->addExtension(new \Twig_Extension_Debug());

    // Twig Extensions
    $view->addExtension(new \Twig_Extensions_Extension_Text());
    $view->addExtension(new \Twig_Extensions_Extension_Array());
    $view->addExtension(new \Twig_Extensions_Extension_Date());

    $view->addExtension(new \Cocur\Slugify\Bridge\Twig\SlugifyExtension(\Cocur\Slugify\Slugify::create()));

    // Flash Messages
    $view->addExtension(new \Knlv\Slim\Views\TwigMessages($container->get('flash')));

    // Base URL
    $view->getEnvironment()->addGlobal('BaseUrl', $container['request']->getUri()->getBaseUrl());

    // Get Params
    $view->getEnvironment()->addGlobal('Params', $container['request']->getParams());

    // Query Params
    $view->getEnvironment()->addGlobal('QueryParams', $container['request']->getQueryParams());

    // TMDB Image Helper
    $view->getEnvironment()->addGlobal('TmdbImage', 'http://image.tmdb.org/t/p/w300');

    // Auth
    $view->getEnvironment()->addGlobal('auth', [
        'check' => $container->auth->check(),
        'user' => $container->auth->user(),
    ]);

    // Return
    return $view;
};

$container['validator'] = function ($container) {
    Val::with('\\Turbo\\Validation\\Rules');
    return new \Turbo\Validation\Validator();
};

// Controllers
$container['HomeController'] = function ($container) {
    return new \Turbo\Controllers\HomeController($container);
};
$container['SearchController'] = function ($container) {
    return new \Turbo\Controllers\SearchController($container);
};
$container['MovieController'] = function ($container) {
    return new \Turbo\Controllers\MovieController($container);
};
$container['AuthController'] = function ($container) {
    return new \Turbo\Controllers\Auth\AuthController($container);
};
$container['PasswordController'] = function ($container) {
    return new \Turbo\Controllers\Auth\PasswordController($container);
};

// Middleware (All Routes)
$app->add(new \Turbo\Middleware\CsrfMiddleware($container));
$app->add($container['csrf']);
$app->add(new \Turbo\Middleware\ValidationErrorsMiddleware($container));

if ($dmode) {
    $app->add(new \Turbo\Middleware\LogMiddleware($container));
}

// CORS Middleware
$app->add(function (Request $request, Response $response, $next) {
    $response = $next($request, $response);
    return $response->withHeader('Access-Control-Allow-Origin', '*')->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Custom Handlers
require_once APP_ROOT . '/src/handlers.php';
