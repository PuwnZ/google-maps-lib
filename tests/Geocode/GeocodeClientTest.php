<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Geocode;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Geocode\GeocodeClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GeocodeClientTest extends TestCase
{
    /** @var string */
    private $googleApiKey;

    /** @var GeocodeClient */
    private $service;

    /** @var \PHPUnit\Framework\MockObject\MockObject|HttpClientInterface */
    private $client;

    /** @var \PHPUnit\Framework\MockObject\MockObject|LoggerInterface */
    private $logger;

    protected function setUp() : void
    {
        parent::setUp();

        $this->googleApiKey = 'mocked-api-key';
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = new GeocodeClient($this->client, $this->logger, $this->googleApiKey);
    }

    public function testGetGeocodeThrow() : void
    {
        $address = 'mocked-address';
        $queryComponents = [
            'mocked-component' => 'mocked-value',
        ];

        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('mocked-exception'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with('mocked-exception', ['address' => $address, 'components' => 'mocked-component:mocked-value']);

        $this->service->getGeocode($address, $queryComponents);
    }

    public function testGetGeocode() : void
    {
        $address = 'mocked-address';
        $queryComponents = [
            'mocked-component' => 'mocked-value',
        ];

        $response = $this->createMock(ResponseInterface::class);

        $this->client->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'https://maps.googleapis.com/maps/api/geocode/json',
                [
                    'query' => [
                        'address' => $address,
                        'key' => $this->googleApiKey,
                        'components' => 'mocked-component:mocked-value',
                    ],
                ]
            )
            ->willReturn($response);

        $response->expects($this->once())
            ->method('toArray')
            ->willReturn(['mocked-response']);

        $actual = $this->service->getGeocode($address, $queryComponents);

        TestCase::assertSame(['mocked-response'], $actual);
    }
}
