<?php

declare(strict_types=1);

namespace Puwnz\Google\Tests\Geocode\DTO;

use PHPUnit\Framework\TestCase;
use Puwnz\Google\Geocode\DTO\GeocodeAddressComponent;

class GeocodeAddressComponentTest extends TestCase
{
    public function testTypes() : void
    {
        $geocodeAddressComponent = new GeocodeAddressComponent();

        $types = [
            'mock-type-1',
            'mock-type-2',
            'mock-type-3',
            'mock-type-4',
        ];

        $geocodeAddressComponent->setTypes(...$types);

        TestCase::assertEquals($types, $geocodeAddressComponent->getTypes());
    }

    public function testLongName() : void
    {
        $geocodeAddressComponent = new GeocodeAddressComponent();

        $longName = 'mock-long-name';

        $geocodeAddressComponent->setLongName($longName);

        TestCase::assertEquals($longName, $geocodeAddressComponent->getLongName());
    }

    public function testShotName() : void
    {
        $geocodeAddressComponent = new GeocodeAddressComponent();

        $shortName = 'mock-short-name';

        $geocodeAddressComponent->setShortName($shortName);

        TestCase::assertEquals($shortName, $geocodeAddressComponent->getShortName());
    }
}
