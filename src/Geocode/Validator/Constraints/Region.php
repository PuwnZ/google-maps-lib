<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Region extends Constraint
{
    public $message = 'Region "{{ key }}" is not supported.';
}
