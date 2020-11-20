<?php

namespace Turbo\Validation\Exceptions;

use \Respect\Validation\Exceptions\ValidationException;

/**
 * MatchesPasswordException
 *
 * @package Turbo\Validation\Exceptions
 *
 */
class MatchesPasswordException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT  => [
            self::STANDARD => 'Sorry {{name}}, we had an ERROR! Try again later!',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => 'Sorry {{name}}, there was an ERROR! Try again. later...',
        ]
    ];
}