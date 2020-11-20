<?php

namespace Turbo\Validation\Rules;

use \Respect\Validation\Rules\AbstractRule;

/**
 * MatchesPassword
 *
 * @package Turbo\Validation\Rules
 *
 */
class MatchesPassword extends AbstractRule
{

    /**
     * @var string
     *
     */
    protected $password;

    /**
     * MatchesPassword Constructor
     *
     * @param $password string
     *
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * validate
     *
     * @param $input string - Password to validate
     * @return bool
     *
     */
    public function validate($input)
    {
        return password_verify($input, $this->password);
    }
}