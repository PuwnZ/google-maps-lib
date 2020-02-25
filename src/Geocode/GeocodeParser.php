<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode;

class GeocodeParser
{
    /** @var GeocodeClient */
    private $geocodeClient;

    /** @var GeocodeResultsFactory */
    private $geocodeResultsFactory;

    public function __construct(GeocodeClient $geocodeClient, GeocodeResultsFactory $geocodeResultsFactory)
    {
        $this->geocodeClient = $geocodeClient;
        $this->geocodeResultsFactory = $geocodeResultsFactory;
    }

    /**
     * @throws Exception\GeocodeComponentQueryException
     */
    public function getGeocodeResults(string $address, array $queryComponents = []) : array
    {
        $response = $this->geocodeClient->getGeocode($address, $queryComponents);

        return $this->geocodeResultsFactory->create($response);
    }
}
