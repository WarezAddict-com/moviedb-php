<?php

// Namespace
namespace Turbo\Models;

// Use Libs
use \Carbon\Carbon;
use \Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @package Turbo\Models
 */
class User extends Model
{
    /**
     * @var string
     *
     */
    protected $table = 'users';

    /**
     * @var  string
     *
     */
    protected $name;
    protected $email;
    protected $created_at;
    protected $updated_at;
    protected $avatar_url;

    /**
     * @var array
     *
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'is_admin',
        'created_at',
        'updated_at',
    ];

    /**
     * setPassword
     *
     * @param $password
     *
     */
    public function setPassword($password)
    {
        $this->update([
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function setName($name)
    {
        $clean = trim($name);
        $this->update(['name' => $clean]);
    }

    public function getUserId()
    {
        return $this->getKey();
    }

    public function getAll()
    {
        return $this->all();
    }

    public function getName()
    {
        return $this->name;
    }

    public function gravatar($email = '', $size = '')
    {

        if ($email && $size) {

            $email = md5(strtolower(trim($email)));

            $gravUrl = "http://www.gravatar.com/avatar/" . $email . "?s=" . $size;

            return '<img class="img-fluid shadow" src="' . $gravUrl . '" border="0" alt="Image">';

        } else {

            $email = '';

            $default = '/images/default_avatar.jpg';

            $size = 65;

            return '<img class="img-fluid shadow" src="' . $default . '" width="' . $size . '" height="' . $size . '" border="0" alt="Image">';
        }
    }

    public function updateLastTime()
    {
        $this->update([
            'updated_at' => Carbon::now(),
        ]);
    }
}