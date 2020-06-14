<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Geocode;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeAddressComponent;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeGeometry;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeResult;
use Puwnz\GoogleMapsLib\Geocode\DTO\Geometry\GeometryLocation;
use Puwnz\GoogleMapsLib\Geocode\GeocodeResultsFactory;

class GeocodeResultsFactoryTest extends TestCase
{
    /** @var GeocodeResultsFactory */
    private $service;

    /** @var \PHPUnit\Framework\MockObject\MockObject|LoggerInterface */
    private $logger;

    protected function setUp() : void
    {
        parent::setUp();

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->service = new GeocodeResultsFactory($this->logger);
    }

    public function testGoogleSendError(): void
    {
        $response = [
            "error_message" => "This API project is not authorized to use this API.",
            "results" => [],
            "status" => "REQUEST_DENIED",
        ];
        $expected = [];

        $this->logger->expects($this->once())
            ->method('error')
            ->with($response['status'], [$response['error_message']]);

        $actual = $this->service->create($response);

        TestCase::assertSame($expected, $actual);
    }

    public function testTransformWithoutResults() : void
    {
        $response = ['results' => []];
        $expected = [];

        $actual = $this->service->create($response);

        TestCase::assertSame($expected, $actual);
    }

    public function testTransformWithout() : void
    {
        $response = [
            'results' => [
                [
                    'address_components' => [
                        [
                            'long_name' => 'mocked-long_name',
                            'short_name' => 'mocked-short_name',
                            'types' => [
                                'route',
                            ],
                        ],
                    ],
                    'geometry' => [
                        'location' => [
                            'lat' => 1.1234556,
                            'lng' => -9.142342,
                        ],
                    ],
                    'formatted_address' => 'mocked-formatted_address',
                    'place_id' => 'mocked-place_id',
                    'types' => [
                        'route',
                    ],
                ],
            ],
        ];

        $expected = [$this->createGeocodeResult($response['results'][0])];

        $actual = $this->service->create($response);

        TestCase::assertEquals($expected, $actual);
    }

    private function createGeocodeResult(array $result) : GeocodeResult
    {
        $geometry = (new GeocodeGeometry())
            ->setLocation(
                (new GeometryLocation())
                ->setLongitude($result['geometry']['location']['lng'])
                ->setLatitude($result['geometry']['location']['lat'])
            );

        $geocodeAddressComponents = [
            (new GeocodeAddressComponent())
            ->setTypes(...$result['address_components'][0]['types'])
            ->setLongName($result['address_components'][0]['long_name'])
            ->setShortName($result['address_components'][0]['short_name']),
        ];

        $geocodeResult = (new GeocodeResult())
            ->setTypes(...$result['types'])
            ->setPlaceId($result['place_id'])
            ->setFormattedAddress($result['formatted_address'])
            ->setGeometry($geometry)
            ->setPartialMatch(false)
            ->setGeocodeAddressComponent(...$geocodeAddressComponents);

        return $geocodeResult;
    }
}
