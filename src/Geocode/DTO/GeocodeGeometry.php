<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\DTO;

use Puwnz\GoogleMapsLib\Geocode\DTO\Geometry\GeometryLocation;

class GeocodeGeometry
{
    /** @var GeometryLocation */
    private $location;

    public function setLocation(GeometryLocation $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getLocation(): GeometryLocation
    {
        return $this->location;
    }
}
