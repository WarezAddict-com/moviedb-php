<?php

// Routes
$app->group('', function () {
    $this->get('/', 'HomeController:index')->setName('home');
    $this->get('/search', 'SearchController:get')->setName('search.get');
    $this->post('/search', 'SearchController:post')->setName('search.post');
    $this->post('/contact', 'ContactController:post')->setName('contact.post');
    $this->get('/contact', 'ContactController:get')->setName('contact.get');
});

$app->group('', function () {
    $this->get('/login', 'AuthController:getSignIn')->setName('getSignIn');
    $this->post('/login', 'AuthController:postSignIn')->setName('postSignIn');
    $this->get('/register', 'AuthController:getSignUp')->setName('getSignUp');
    $this->post('/register', 'AuthController:postSignUp')->setName('postSignUp');
})->add(new \Turbo\Middleware\GuestMiddleware($container));

$app->group('', function () {
    $this->get('/logout', 'AuthController:getSignOut')->setName('logout');
    $this->get('/profile', 'AuthController:getProfile')->setName('profile');
    $this->get('/profile/password/change', 'PasswordController:getChangePassword')->setName('get.password.change');
    $this->post('/profile/password/change', 'PasswordController:postChangePassword')->setName('post.password.change');
})->add(new \Turbo\Middleware\AuthMiddleware($container));
