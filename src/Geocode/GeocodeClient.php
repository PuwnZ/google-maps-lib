<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\QueryBuilderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocodeClient
{
    /** @var HttpClientInterface */
    private $client;

    /** @var string */
    private $googleApiKey;

    /** @var LoggerInterface */
    private $logger;

    /** @var CacheItemPoolInterface */
    private $cache;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger, CacheItemPoolInterface $cache, string $googleApiKey)
    {
        $this->client = $client;
        $this->googleApiKey = $googleApiKey;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @throws GeocodeComponentQueryException
     *
     * @deprecated this method is deprecated and will be removed in puwnz/google-maps-lib 1.0, use \Puwnz\GoogleMapsLib\Geocode\GeocodeClient::getGeocodeWithBuilder instead
     */
    public function getGeocode(string $address, array $queryComponents) : array
    {
        $components = $this->buildQueryComponents($queryComponents);

        try {
            $response = $this->client->request(
                'GET',
                'https://maps.googleapis.com/maps/api/geocode/json',
                [
                    'query' => [
                        'address' => $address,
                        'key' => $this->googleApiKey,
                        'components' => $components,
                    ],
                ]
            );

            return $response->toArray();
        } catch (\Throwable $e) {
            $this->logger->error(
                $e->getMessage(),
                ['address' => $address, 'components' => $components]
            );

            return [];
        }
    }

    /**
     * @throws GeocodeComponentQueryException
     *
     * @deprecated this method is deprecated and will be removed in puwnz/google-maps-lib 1.0
     */
    private function buildQueryComponents(array $queryComponents) : string
    {
        $components = [];

        foreach ($queryComponents as $keyComponent => $valueComponent) {
            $components[] = \sprintf('%s:%s', $keyComponent, $valueComponent);
        }

        return \implode('|', $components);
    }

    public function getGeocodeWithBuilder(QueryBuilderInterface $queryBuilder) : array
    {
        $query = $queryBuilder->getQuery();

        try {
            $queries = \array_merge(
                $query,
                [
                    'key' => $this->googleApiKey,
                ]
            );

            $cacheKey = \json_encode($queries);

            $item = $this->cache->getItem($cacheKey);

            if ($item->isHit()) {
                $this->logger->debug(
                    'Get map\'s result from cache',
                    $cacheKey
                );

                return \json_decode($item->get(), true);
            }

            $response = $this->client->request(
                'GET',
                'https://maps.googleapis.com/maps/api/geocode/json',
                [
                    'query' => $queries,
                ]
            );

            $arrayResponse = $response->toArray();

            $item->set(\json_encode($arrayResponse));

            return $arrayResponse;
        } catch (\Throwable $e) {
            $this->logger->error(
                $e->getMessage(),
                $query
            );

            return [];
        }
    }
}
