<?php

// Namespace
namespace Turbo\Validation;

// Use Libs
use \Respect\Validation\Exceptions\NestedValidationException;

/**
 * Validator
 *
 * @package Turbo\Validation
 *
 */
class Validator
{
    /**
     * @var
     */
    protected $errors;

    /**
     * validate
     *
     * @return $this
     *
     */
    public function validate($request, array $rules)
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($request->getParam($field));
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages();
            }
        }
        $_SESSION['errors'] = $this->errors;
        return $this;
    }

    /**
     * failed
     *
     * @return bool
     *
     */
    public function failed()
    {
        return !empty($this->errors);
    }
}