<?php

declare(strict_types=1);

namespace Puwnz\Google\Geocode;

use Psr\Log\LoggerInterface;
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

    private function buildQueryComponents(array $queryComponents) : string
    {
        $components = [];

        foreach ($queryComponents as $keyComponent => $valueComponent) {
            $components[] = \sprintf('%s:%s', $keyComponent, $valueComponent);
        }

        return \implode('|', $components);
    }
}
