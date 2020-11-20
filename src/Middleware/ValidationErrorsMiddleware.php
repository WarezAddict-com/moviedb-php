<?php

namespace Turbo\Middleware;

use \Turbo\Middleware\Middleware;

class ValidationErrorsMiddleware extends \Turbo\Middleware\Middleware
{

    public function __invoke($request, $response, $next)
    {
        if (isset($_SESSION['errors'])) {

            $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);

            unset($_SESSION['errors']);
        }

        $response = $next($request, $response);
        return $response;
    }
}