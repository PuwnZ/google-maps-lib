<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode;

use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Geocode\GeocodeFactory;
use Puwnz\GoogleMapsLib\Geocode\GeocodeParser;

class GeocodeFactoryTest extends TestCase
{
    public function testCreate() : void
    {
        $googleApiKey = 'mocked-api-key';

        $geocode = GeocodeFactory::create($googleApiKey);

        TestCase::assertInstanceOf(GeocodeParser::class, $geocode);
    }
}
