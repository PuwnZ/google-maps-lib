<?php

declare(strict_types=1);

namespace Puwz\Google\Tests\Geocode\Geocode;

use PHPUnit\Framework\TestCase;
use Puwnz\Google\Geocode\GeocodeClient;
use Puwnz\Google\Geocode\GeocodeParser;
use Puwnz\Google\Geocode\GeocodeResultsFactory;

class GeocodeParserTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject|GeocodeClient */
    private $geocodeClient;

    /** @var \PHPUnit\Framework\MockObject\MockObject|GeocodeResultsFactory */
    private $geocodeResultsFactory;

    /** @var GeocodeParser */
    private $service;

    protected function setUp() : void
    {
        parent::setUp();

        $this->geocodeClient = $this->createMock(GeocodeClient::class);
        $this->geocodeResultsFactory = $this->createMock(GeocodeResultsFactory::class);

        $this->service = new GeocodeParser($this->geocodeClient, $this->geocodeResultsFactory);
    }

    public function testGetGeocodeResult() : void
    {
        $address = 'mocked-address';
        $response = [];
        $expected = [];

        $this->geocodeClient->expects($this->once())
            ->method('getGeocode')
            ->with($address, [])
            ->willReturn($response);

        $this->geocodeResultsFactory->expects($this->once())
            ->method('create')
            ->with($response)
            ->willReturn($expected);

        $actual = $this->service->getGeocodeResults($address);

        TestCase::assertSame($expected, $actual);
    }
}
