<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Geocode;

use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Geocode\GeocodeClient;
use Puwnz\GoogleMapsLib\Geocode\GeocodeParser;
use Puwnz\GoogleMapsLib\Geocode\GeocodeResultsFactory;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\AddressQueryBuilder;

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

        $queryBuilder = new AddressQueryBuilder($address);

        $this->geocodeClient->expects($this->once())
            ->method('getGeocodeWithBuilder')
            ->with($queryBuilder)
            ->willReturn($response);

        $this->geocodeResultsFactory->expects($this->once())
            ->method('create')
            ->with($response)
            ->willReturn($expected);

        $actual = $this->service->getGeocodeByBuilder($queryBuilder);

        TestCase::assertSame($expected, $actual);
    }
}
