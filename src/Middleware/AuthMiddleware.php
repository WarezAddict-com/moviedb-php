<?php

// Namespace
namespace Turbo\Middleware;

// Use Libs
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// AuthMiddleware
class AuthMiddleware extends \Turbo\Middleware\Middleware
{

    public function __invoke(Request $request, Response $response, $next)
    {

        if (!$this->container->auth->check()) {

            $this->container->flash->addMessageNow('info', 'Error! Please Login Or Register!');

            return $response->withRedirect($this->container->router->pathFor('getSignIn'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
