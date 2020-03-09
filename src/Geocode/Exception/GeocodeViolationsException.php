<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Exception;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class GeocodeViolationsException extends \Exception
{
    public function __construct(ConstraintViolationListInterface $constraintViolationList, int $code = 0, \Throwable $previous = null)
    {
        $message = '';

        /** @var ConstraintViolation $constraint */
        foreach ($constraintViolationList as $constraint) {
            $message .= $constraint->getMessage() . PHP_EOL;
        }

        parent::__construct($message, $code, $previous);
    }
}
