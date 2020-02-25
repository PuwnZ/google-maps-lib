<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode;

use Puwnz\GoogleMapsLib\Geocode\Exception\GeocodeComponentQueryException;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\AddressQueryBuilder;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\QueryBuilderInterface;

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
     * @deprecated this method is deprecated and will be removed in puwnz/google-maps-lib 1.0, use \Puwnz\GoogleMapsLib\Geocode\GeocodeParser::getGeocodeByAddress instead
     *
     * @throws GeocodeComponentQueryException
     */
    public function getGeocodeResults(string $address, array $queryComponents = []) : array
    {
        $addressQuery = new AddressQueryBuilder($address, $queryComponents);

        return $this->getGeocodeByBuilder($addressQuery);
    }

    /**
     * @throws GeocodeComponentQueryException
     */
    public function getGeocodeByBuilder(QueryBuilderInterface $queryBuilder) : array
    {
        $response = $this->geocodeClient->getGeocodeWithBuilder($queryBuilder);

        return $this->geocodeResultsFactory->create($response);
    }
}
