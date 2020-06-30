<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Geocode;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Geocode\GeocodeClient;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\QueryBuilderInterface;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeComponentQueryType;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GeocodeClientTest extends TestCase
{
    /** @var string */
    private $googleApiKey;

    /** @var GeocodeClient */
    private $service;

    /** @var MockObject|HttpClientInterface */
    private $client;

    /** @var MockObject|LoggerInterface */
    private $logger;

    /** @var MockObject|CacheItemPoolInterface */
    private $cache;

    protected function setUp() : void
    {
        parent::setUp();

        $this->googleApiKey = 'mocked-api-key';
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->cache = $this->createMock(CacheItemPoolInterface::class);

        $this->service = new GeocodeClient($this->client, $this->logger, $this->cache, $this->googleApiKey);
    }

    public function testGetGeocodeThrow() : void
    {
        $address = 'mocked-address';
        $queryComponents = [
            GeocodeComponentQueryType::COUNTRY => 'mocked-value',
        ];

        $this->client->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('mocked-exception'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with('mocked-exception', ['address' => $address, 'components' => GeocodeComponentQueryType::COUNTRY . ':mocked-value']);

        $this->service->getGeocode($address, $queryComponents);
    }

    public function testGetGeocode() : void
    {
        $address = 'mocked-address';
        $queryComponents = [
            GeocodeComponentQueryType::COUNTRY => 'mocked-value',
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
                        'components' => GeocodeComponentQueryType::COUNTRY . ':mocked-value',
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

    public function testGetGeocodeByBuilderThrowable() : void
    {
        $query = [
            'address' => 'mocked-address',
            'components' => GeocodeComponentQueryType::COUNTRY . ':mocked-value',
        ];

        $queryBuilder = $this->createMock(QueryBuilderInterface::class);

        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $this->cache->expects($this->once())
            ->method('getItem')
            ->willThrowException(new \Exception('mocked-error'));

        $this->logger->expects($this->once())
            ->method('error')
            ->with('mocked-error', $query);

        $actual = $this->service->getGeocodeWithBuilder($queryBuilder);

        static::assertEquals([], $actual);
    }

    public function testGetGeocodeByBuilderWithoutCache() : void
    {
        $query = [
            'address' => 'mocked-address',
            'components' => GeocodeComponentQueryType::COUNTRY . ':mocked-value',
        ];

        $cacheKey = '17fb29ab968b777d989d516f1c291c65';

        $queryBuilder = $this->createMock(QueryBuilderInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $item = $this->createMock(ItemInterface::class);

        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $this->cache->expects($this->once())
            ->method('getItem')
            ->with($cacheKey)
            ->willReturn($item);

        $item->expects($this->once())
            ->method('isHit')
            ->willReturn(false);

        $this->client->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'https://maps.googleapis.com/maps/api/geocode/json',
                [
                    'query' => [
                        'address' => 'mocked-address',
                        'key' => $this->googleApiKey,
                        'components' => GeocodeComponentQueryType::COUNTRY . ':mocked-value',
                    ],
                ]
            )
            ->willReturn($response);

        $response->expects($this->once())
            ->method('toArray')
            ->willReturn(['mocked-response']);

        $item->expects($this->once())
            ->method('set')
            ->with(\json_encode(['mocked-response']));

        $this->cache->expects($this->once())
            ->method('save')
            ->with($item);

        $actual = $this->service->getGeocodeWithBuilder($queryBuilder);

        TestCase::assertSame(['mocked-response'], $actual);
    }

    public function testGetGeocodeByBuilderWithCache() : void
    {
        $query = [
            'address' => 'mocked-address',
            'components' => GeocodeComponentQueryType::COUNTRY . ':mocked-value',
        ];

        $cacheKey = '17fb29ab968b777d989d516f1c291c65';

        $queryBuilder = $this->createMock(QueryBuilderInterface::class);
        $item = $this->createMock(ItemInterface::class);

        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $this->cache->expects($this->once())
            ->method('getItem')
            ->with($cacheKey)
            ->willReturn($item);

        $item->expects($this->once())
            ->method('isHit')
            ->willReturn(true);

        $this->logger->expects($this->once())
            ->method('debug')
            ->with('Get map\'s result from cache', ['cacheKey' => $cacheKey]);

        $item->expects($this->once())
            ->method('get')
            ->willReturn('{"0": "mocked-response"}');

        $actual = $this->service->getGeocodeWithBuilder($queryBuilder);

        TestCase::assertSame(['mocked-response'], $actual);
    }
}
