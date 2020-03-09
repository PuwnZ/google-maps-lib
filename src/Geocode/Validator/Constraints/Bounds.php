<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Bounds extends Constraint
{
    public $messageKeyNotExists = 'Bounds key "{{ key }}" not exists.';
    public $messageBoundsCounter = 'Bounds key "{{ key }}" are not valid.';
}
