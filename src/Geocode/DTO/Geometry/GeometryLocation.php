<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\DTO\Geometry;

class GeometryLocation
{
    /** @var float */
    private $lat;

    /** @var float */
    private $lng;

    public function getLatitude(): float
    {
        return $this->lat;
    }

    public function setLatitude(float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->lng;
    }

    public function setLongitude(float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }
}
