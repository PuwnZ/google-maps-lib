<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Client;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Common\Client\ClientInterface;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\GeocodeQueryBuilder;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocodeClient implements ClientInterface
{
    /** @var HttpClientInterface */
    private $client;

    /** @var string */
    private $googleApiKey;

    /** @var LoggerInterface */
    private $logger;

    /** @var CacheInterface */
    private $cache;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger, CacheItemPoolInterface $googleMaps, string $googleApiKey)
    {
        $this->client = $client;
        $this->googleApiKey = $googleApiKey;
        $this->cache = $googleMaps;
        $this->logger = $logger;
    }

    public function call(QueryBuilderInterface $queryBuilder): array
    {
        $query = $queryBuilder->getQuery();

        try {
            $queries = array_merge(
                $query,
                [
                    'key' => $this->googleApiKey,
                ]
            );

            $cacheKey = md5(json_encode($queries));

            $item = $this->cache->getItem($cacheKey);

            if ($item->isHit()) {
                $this->logger->debug(
                    'Get map\'s result from cache',
                    ['cacheKey' => $cacheKey]
                );

                return json_decode($item->get(), true);
            }

            $response = $this->client->request(
                'GET',
                'https://maps.googleapis.com/maps/api/geocode/json',
                [
                    'query' => $queries,
                ]
            );

            $arrayResponse = $response->toArray();

            $item->set(json_encode($arrayResponse));
            $this->cache->save($item);

            return $arrayResponse;
        } catch (\Throwable $e) {
            $this->logger->error(
                $e->getMessage(),
                $query
            );

            return [];
        }
    }

    public function supports(QueryBuilderInterface $queryBuilder): bool
    {
        return $queryBuilder instanceof GeocodeQueryBuilder;
    }
}
