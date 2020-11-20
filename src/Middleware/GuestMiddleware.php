<?php

namespace Turbo\Middleware;

use \Turbo\Middleware\Middleware;

/**
 * GuestMiddleware Class
 *
 * @package Turbo\Middleware
 *
 */
class GuestMiddleware extends \Turbo\Middleware\Middleware
{

    public function __invoke($request, $response, $next)
    {

        if ($this->container->auth->check()) {
            return $response->withRedirect($this->container->router->pathFor('home'));
        }

        $response = $next($request, $response);
        return $response;
    }

}
