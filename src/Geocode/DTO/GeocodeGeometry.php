<?php

declare(strict_types=1);

namespace Puwnz\Google\Geocode\DTO;

use Puwnz\Google\Geocode\DTO\Geometry\GeometryLocation;

class GeocodeGeometry
{
    /** @var GeometryLocation */
    private $location;

    public function setLocation(GeometryLocation $location) : self
    {
        $this->location = $location;

        return $this;
    }

    public function getLocation() : GeometryLocation
    {
        return $this->location;
    }
}
