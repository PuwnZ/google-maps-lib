<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Language extends Constraint
{
    public $message = 'Language "{{ key }}" is not supported.';
}
