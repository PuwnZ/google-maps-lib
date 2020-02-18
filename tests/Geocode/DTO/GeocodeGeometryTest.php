<?php

declare(strict_types=1);

namespace Puwnz\Google\Tests\Geocode\DTO;

use PHPUnit\Framework\TestCase;
use Puwnz\Google\Geocode\DTO\GeocodeGeometry;
use Puwnz\Google\Geocode\DTO\Geometry\GeometryLocation;

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
