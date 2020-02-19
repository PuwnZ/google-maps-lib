<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\DTO;

use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeGeometry;
use Puwnz\GoogleMapsLib\Geocode\DTO\Geometry\GeometryLocation;

class GeocodeGeometryTest extends TestCase
{
    public function testLocation() : void
    {
        $geocodeGeometry = new GeocodeGeometry();

        $location = $this->createMock(GeometryLocation::class);
        $geocodeGeometry->setLocation($location);

        TestCase::assertEquals($location, $geocodeGeometry->getLocation());
    }
}
