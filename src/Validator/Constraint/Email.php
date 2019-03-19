<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraints\EmailValidator;

/**
 * Add message parameter to constructor to create more informative error messages.
 */
class Email extends \Symfony\Component\Validator\Constraints\Email
{
    /**
     * Email constructor.
     * @param null|string $message
     * @param null $options
     */
    public function __construct(?string $message = null, $options = null)
    {
        parent::__construct($options);
        if (null !== $message) {
            $this->message = $message;
        }
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return EmailValidator::class;
    }
}
