<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Client;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;
use Puwnz\GoogleMapsLib\Geocode\Client\GeocodeClient;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\GeocodeQueryBuilder;
use Symfony\Component\Cache\Adapter\TraceableAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GeocodeClientTest extends TestCase
{
    /** @var MockObject|HttpClientInterface */
    private $client;

    /** @var MockObject|CacheItemPoolInterface */
    private $cache;

    /** @var MockObject|LoggerInterface */
    private $logger;

    /** @var string */
    private $googleApiKey;

    /** @var GeocodeClient */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createMock(HttpClientInterface::class);
        $this->cache = $this->createMock(TraceableAdapter::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->googleApiKey = 'google-api-key';

        $this->service = new GeocodeClient($this->client, $this->logger, $this->cache, $this->googleApiKey);
    }

    public function testCallThrow(): void
    {
        $queryBuilder = $this->createMock(QueryBuilderInterface::class);

        $query = ['query' => 'mocked-query'];

        $queries = [
            'query' => 'mocked-query',
            'key' => $this->googleApiKey,
        ];

        $cacheKey = md5(json_encode($queries));
        $cacheItemValue = '{"value": "mocked-value"}';

        $cacheItem = $this->createMock(CacheItemInterface::class);

        $queryBuilder->expects(self::once())
            ->method('getQuery')
            ->willReturn($query);

        $this->cache->expects(self::once())
            ->method('getItem')
            ->willThrowException(new \Exception('mocked exception'));

        $this->logger->expects(self::once())
            ->method('error')
            ->with(
                'mocked exception',
                ['query' => 'mocked-query']
            );

        $actual = $this->service->call($queryBuilder);

        self::assertSame([], $actual);
    }

    public function testCallHitted(): void
    {
        $queryBuilder = $this->createMock(QueryBuilderInterface::class);

        $query = ['query' => 'mocked-query'];

        $queries = [
            'query' => 'mocked-query',
            'key' => $this->googleApiKey,
        ];

        $cacheKey = md5(json_encode($queries));
        $cacheItemValue = '{"value": "mocked-value"}';

        $cacheItem = $this->createMock(CacheItemInterface::class);

        $queryBuilder->expects(self::once())
            ->method('getQuery')
            ->willReturn($query);

        $this->cache->expects(self::once())
            ->method('getItem')
            ->with($cacheKey)
            ->willReturn($cacheItem);

        $cacheItem->expects(self::once())
            ->method('isHit')
            ->willReturn(true);

        $this->logger->expects(self::once())
            ->method('debug')
            ->with(
                'Get map\'s result from cache',
                ['cacheKey' => $cacheKey]
            );

        $cacheItem->expects(self::once())
            ->method('get')
            ->willReturn($cacheItemValue);

        $actual = $this->service->call($queryBuilder);

        self::assertSame(['value' => 'mocked-value'], $actual);
    }

    public function testCall(): void
    {
        $queryBuilder = $this->createMock(QueryBuilderInterface::class);

        $query = ['query' => 'mocked-query'];

        $queries = [
            'query' => 'mocked-query',
            'key' => $this->googleApiKey,
        ];

        $cacheKey = md5(json_encode($queries));

        $cacheItem = $this->createMock(CacheItemInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $queryBuilder->expects(self::once())
            ->method('getQuery')
            ->willReturn($query);

        $this->cache->expects(self::once())
            ->method('getItem')
            ->with($cacheKey)
            ->willReturn($cacheItem);

        $cacheItem->expects(self::once())
            ->method('isHit')
            ->willReturn(false);

        $this->client->expects(self::once())
            ->method('request')
            ->with(
                'GET',
                'https://maps.googleapis.com/maps/api/geocode/json',
                [
                    'query' => $queries,
                ]
            )
            ->willReturn($response);

        $response->expects(self::once())
            ->method('toArray')
            ->willReturn(['value' => 'mocked-value']);

        $cacheItem->expects(self::once())
            ->method('set')
            ->with('{"value":"mocked-value"}');

        $this->cache->expects(self::once())
            ->method('save')
            ->with($cacheItem);

        $actual = $this->service->call($queryBuilder);

        self::assertSame(['value' => 'mocked-value'], $actual);
    }

    public function testSupports(): void
    {
        self::assertFalse($this->service->supports($this->createMock(QueryBuilderInterface::class)));
        static::asserttrue($this->service->supports($this->createMock(GeocodeQueryBuilder::class)));
    }
}
