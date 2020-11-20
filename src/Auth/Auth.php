<?php

// Namespace
namespace Turbo\Auth;

// Use Libs
use \Turbo\Models\User;

class Auth
{

    // Check
    public function check()
    {
        if (isset($_SESSION['user'])) {
            return true;
        }
        return false;
    }

    // User
    public function user()
    {
        if (isset($_SESSION['user'])) {
            return User::find($_SESSION['user']);
        }
        return false;
    }

    public function allUsers()
    {
        return User::all();
    }

    public function updateLoginTime()
    {
        if (isset($_SESSION['user'])) {

            $user = User::find($_SESSION['user']);
            $user->updateLastTime();
        }
        return false;
    }

    public function attempt($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (isset($user) && password_verify($password, $user->password)) {
            $_SESSION['user'] = $user->id;
            return true;
        }
        return false;
    }

    public function logout()
    {
        unset($_SESSION['user']);
    }

    public function verifyAdmin($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (!isset($user)) {
            return false;
        }

        if ($user->is_admin === "1" && password_verify($password, $user->password)) {
            $_SESSION['user'] = $user->id;
            return true;
        }
        return false;
    }

    public function checkAdmin()
    {
        if (isset($_SESSION['user'])) {

            $user = User::find($_SESSION['user']);

            if ($user->is_admin === "1") {
                return true;
            }
        }
        return false;
    }
}