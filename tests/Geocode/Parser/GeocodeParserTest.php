<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Parser;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;
use Puwnz\GoogleMapsLib\Geocode\Parser\GeocodeParser;
use Puwnz\GoogleMapsLib\Geocode\Parser\GeocodeResultsFactory;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\GeocodeQueryBuilder;

class GeocodeParserTest extends TestCase
{
    /** @var MockObject|GeocodeResultsFactory */
    private $geocodeResultsFactory;

    /** @var GeocodeParser */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->geocodeResultsFactory = $this->createMock(GeocodeResultsFactory::class);
        $this->service = new GeocodeParser($this->geocodeResultsFactory);
    }

    public function testParse() : void
    {
        $response = ['response' => 'mocked'];

        $this->geocodeResultsFactory->expects(static::once())
            ->method('create')
            ->with($response)
            ->willReturn($response);

        $actual = $this->service->parse($response);

        static::assertSame(['response' => 'mocked'], $actual);
    }

    public function testSupports() : void
    {
        static::assertFalse($this->service->supports($this->createMock(QueryBuilderInterface::class)));
        static::assertTrue($this->service->supports($this->createMock(GeocodeQueryBuilder::class)));
    }
}
