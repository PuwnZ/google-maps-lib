<?php

declare(strict_types=1);

namespace Puwnz\Google\Tests\Geocode;

use PHPUnit\Framework\TestCase;
use Puwnz\Google\Geocode\GeocodeFactory;
use Puwnz\Google\Geocode\GeocodeParser;

class GeocodeFactoryTest extends TestCase
{
    public function testCreate() : void
    {
        $googleApiKey = 'mocked-api-key';

        $geocode = GeocodeFactory::create($googleApiKey);

        TestCase::assertInstanceOf(GeocodeParser::class, $geocode);
    }
}
