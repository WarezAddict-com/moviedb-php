<?php

// Namespace
namespace Turbo\Controllers\Auth;

// Use Libs
use \Turbo\Controllers\Controller;
use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Turbo\Models\User;
use \Respect\Validation\Validator as val;
use \Carbon\Carbon;

class AuthController extends \Turbo\Controllers\Controller
{

    public function getSignOut(Request $request, Response $response)
    {
        // Logout
        $this->auth->logout();

        // Flash Message
        $this->flash->addMessageNow('info', 'You are logged out!');

        // Return Home
        return $response->withRedirect($this->router->pathFor('home'));
    }

    public function getSignIn(Request $request, Response $response, array $args)
    {
        // $this->flash->addMessageNow('info', 'Please login or register an account!');
        return $this->view->render($response, 'login.twig');
    }

    public function postSignIn(Request $request, Response $response, array $args)
    {
        $auth = $this->auth->attempt($request->getParam('email'), $request->getParam('password'));

        if (isset($auth)) {

            $this->flash->addMessageNow('info', 'You are logged in...');

            return $response->withRedirect($this->router->pathFor('home'));

        }

        $this->logger->info('AUTH', ['ERROR' => 'Failed Login']);
        $this->flash->addMessageNow('info', 'Error! Try again later...');
        return $response->withRedirect($this->router->pathFor('getSignIn'));
    }

    public function getSignUp(Request $request, Response $response)
    {
        return $this->view->render($response, 'register.twig');
    }

    public function postSignUp(Request $request, Response $response)
    {
        $validation = $this->validator->validate($request, [
            'email' => val::noWhitespace()->notEmpty()->email(),
            'name' => val::notEmpty()->alpha(),
            'password' => val::noWhitespace()->notEmpty(),
        ]);

        if ($validation->failed()) {
            $this->flash->addMessageNow('info', 'Error! Try again...');
            return $response->withRedirect($this->router->pathFor('getSignUp'));
        }

        $emailChk = User::where('email', $request->getParam('email'))->count();

        if ($emailChk === 0) {

            $user = User::create([
                'name' => $request->getParam('name'),
                'email' => $request->getParam('email'),
                'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT, ['cost' => 10]),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
                'is_admin' => 0,
                'avatar_url' => '/images/default_avatar.jpg',
            ]);

            $this->logger->info('NEW_USER', [
                'name' => $request->getParam('name'),
                'email' => $request->getParam('email'),
                'created_at' => \Carbon\Carbon::now(),
            ]);

            $this->auth->attempt($user->email, $request->getParam('password'));

            $this->flash->addMessageNow('info', 'Account created! You can now login!');

            return $response->withRedirect($this->router->pathFor('home'));
        }

        $this->flash->addMessageNow('info', 'Error! Email is in use!');
        return $response->withRedirect($this->router->pathFor('getSignUp'));
    }

    public function getProfile(Request $request, Response $response, array $args)
    {
        return $this->view->render($response, 'profile.twig');
    }
}