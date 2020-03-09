<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class QueryComponents extends Constraint
{
    public $keyIsWrong = 'Query components key "{{ key }}" not exists';
}
