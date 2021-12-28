<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\DTO;

use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeAddressComponent;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeGeometry;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeResult;

class GeocodeResultTest extends TestCase
{
    public function testGeocodeAddressComponent(): void
    {
        $geocodeResult = new GeocodeResult();

        $values = [
            $this->createMock(GeocodeAddressComponent::class),
            $this->createMock(GeocodeAddressComponent::class),
            $this->createMock(GeocodeAddressComponent::class),
        ];

        $geocodeResult->setGeocodeAddressComponent(...$values);

        TestCase::assertEquals($values, $geocodeResult->getGeocodeAddressComponents());
    }

    public function testFormattedAddress(): void
    {
        $geocodeResult = new GeocodeResult();
        $value = 'mock-formatted_address';
        $geocodeResult->setFormattedAddress($value);

        TestCase::assertEquals($value, $geocodeResult->getFormattedAddress());
    }

    public function testTypes(): void
    {
        $geocodeResult = new GeocodeResult();
        $values = [
            'mocked-type-1',
            'mocked-type-2',
            'mocked-type-3',
        ];
        $geocodeResult->setTypes(...$values);

        TestCase::assertEquals($values, $geocodeResult->getTypes());
    }

    public function testPlaceId(): void
    {
        $geocodeResult = new GeocodeResult();
        $value = 'mock-place_id';
        $geocodeResult->setPlaceId($value);

        TestCase::assertEquals($value, $geocodeResult->getPlaceId());
    }

    public function testPartialMatch(): void
    {
        $geocodeResult = new GeocodeResult();
        $value = true;
        $geocodeResult->setPartialMatch($value);

        TestCase::assertEquals($value, $geocodeResult->isPartialMatch());
    }

    public function testGeometry(): void
    {
        $geocodeResult = new GeocodeResult();
        $value = $this->createMock(GeocodeGeometry::class);
        $geocodeResult->setGeometry($value);

        TestCase::assertEquals($value, $geocodeResult->getGeometry());
    }
}
