<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Exception;

class GeocodeComponentQueryException extends \Exception
{
    public function __construct($keyComponent, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(\sprintf('Geocode component query not found "%s"', $keyComponent), $code, $previous);
    }
}
