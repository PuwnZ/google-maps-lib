<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode;

use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Geocode\Exception\GeocodeComponentQueryException;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\QueryBuilderInterface;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeComponentQueryType;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GeocodeClient
{
    /** @var HttpClientInterface */
    private $client;

    /** @var string */
    private $googleApiKey;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger, string $googleApiKey)
    {
        $this->client = $client;
        $this->googleApiKey = $googleApiKey;
        $this->logger = $logger;
    }

    /**
     * @deprecated this method is deprecated and will be removed in puwnz/google-maps-lib 1.0, use \Puwnz\GoogleMapsLib\Geocode\GeocodeClient::getGeocodeWithBuilder instead
     *
     * @throws GeocodeComponentQueryException
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
     * @deprecated this method is deprecated and will be removed in puwnz/google-maps-lib 1.0
     *
     * @throws GeocodeComponentQueryException
     */
    private function buildQueryComponents(array $queryComponents) : string
    {
        $components = [];

        foreach ($queryComponents as $keyComponent => $valueComponent) {
            if (!\in_array($keyComponent, GeocodeComponentQueryType::TYPES)) {
                throw new GeocodeComponentQueryException($keyComponent);
            }

            $components[] = \sprintf('%s:%s', $keyComponent, $valueComponent);
        }

        return \implode('|', $components);
    }

    public function getGeocodeWithBuilder(QueryBuilderInterface $queryBuilder) : array
    {
        $query = $queryBuilder->getQuery();

        try {
            $response = $this->client->request(
                'GET',
                'https://maps.googleapis.com/maps/api/geocode/json',
                [
                    'query' => \array_merge(
                        $query,
                        [
                            'key' => $this->googleApiKey,
                        ]
                    ),
                ]
            );

            return $response->toArray();
        } catch (\Throwable $e) {
            $this->logger->error(
                $e->getMessage(),
                $query
            );

            return [];
        }
    }
}
