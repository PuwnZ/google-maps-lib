<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Geocode;

use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Geocode\GeocodeClient;
use Puwnz\GoogleMapsLib\Geocode\GeocodeParser;
use Puwnz\GoogleMapsLib\Geocode\GeocodeResultsFactory;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\GeocodeQueryBuilder;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeComponentQueryType;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\QueryComponents;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    public function testGetGeocodeResults() : void
    {
        $address = 'mocked-address';
        $response = [];
        $expected = [];

        $components = [
            GeocodeComponentQueryType::COUNTRY => 'FR',
        ];

        $this->geocodeClient->expects($this->once())
            ->method('getGeocodeWithBuilder')
            ->with($this->isInstanceOf(GeocodeQueryBuilder::class))
            ->willReturn($response);

        $this->geocodeResultsFactory->expects($this->once())
            ->method('create')
            ->with($response)
            ->willReturn($expected);

        $actual = $this->service->getGeocodeResults($address, $components);

        TestCase::assertSame($expected, $actual);
    }

    public function testGetGeocodeByBuilder() : void
    {
        $address = 'mocked-address';
        $response = [];
        $expected = [];

        $components = [
            GeocodeComponentQueryType::COUNTRY => 'FR',
        ];

        $bounds = [
            'northeast' => [
                'lat' => 0.0,
                'lng' => 1.0,
            ],
            'southwest' => [
                'lat' => -0.0,
                'lng' => -1.0,
            ],
        ];

        $validator = $this->createMock(ValidatorInterface::class);
        $queryBuilder = new GeocodeQueryBuilder($validator);

        $violations = $this->createMock(ConstraintViolationListInterface::class);

        $validator->expects($this->exactly(2))
            ->method('validate')
            ->withConsecutive([$components, [new QueryComponents()]], [$bounds, [new Bounds()]])
            ->willReturn($violations);

        $violations->expects($this->exactly(2))
            ->method('count')
            ->willReturn(0);

        $queryBuilder->setAddress($address)
            ->setComponents($components)
            ->setBounds($bounds);

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
