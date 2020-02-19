<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\DTO\Geometry;

use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Geocode\DTO\Geometry\GeometryLocation;

class GeometryLocationTest extends TestCase
{
    public function testLatitude() : void
    {
        $geometryLocation = new GeometryLocation();
        $value = 0.01;

        $geometryLocation->setLatitude($value);
        TestCase::assertEquals($value, $geometryLocation->getLatitude());
    }

    public function testLongitude() : void
    {
        $geometryLocation = new GeometryLocation();
        $value = 0.02;

        $geometryLocation->setLongitude($value);
        TestCase::assertEquals($value, $geometryLocation->getLongitude());
    }
}
