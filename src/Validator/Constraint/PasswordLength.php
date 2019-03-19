<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LengthValidator;

class PasswordLength extends Length
{
    /**
     * PasswordLength constructor.
     * @param string $minMessage
     * @param null $options
     */
    public function __construct($options = null, string $minMessage = null)
    {
        if ($minMessage) {
            $this->minMessage = $minMessage;
        }
        parent::__construct($options);
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return LengthValidator::class;
    }
}