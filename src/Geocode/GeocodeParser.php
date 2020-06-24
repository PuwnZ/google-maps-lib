<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode;

use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\GeocodeQueryBuilder;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\QueryBuilderInterface;
use Symfony\Component\Validator\Validation;

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
     * @deprecated this method is deprecated and will be removed in puwnz/google-maps-lib 1.0, use \Puwnz\GoogleMapsLib\Geocode\GeocodeParser::getGeocodeByBuilder instead
     */
    public function getGeocodeResults(string $address, array $queryComponents = []) : array
    {
        $addressQuery = new GeocodeQueryBuilder(Validation::createValidator());

        $addressQuery->setAddress($address)
            ->setComponents($queryComponents);

        return $this->getGeocodeByBuilder($addressQuery);
    }

    public function getGeocodeByBuilder(QueryBuilderInterface $queryBuilder) : array
    {
        $response = $this->geocodeClient->getGeocodeWithBuilder($queryBuilder);

        return $this->geocodeResultsFactory->create($response);
    }
}
