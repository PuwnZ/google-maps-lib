<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Parser;

use Puwnz\GoogleMapsLib\Common\Parser\ParserInterface;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\GeocodeQueryBuilder;

class GeocodeParser implements ParserInterface
{
    /** @var GeocodeResultsFactory */
    private $geocodeResultsFactory;

    public function __construct(GeocodeResultsFactory $geocodeResultsFactory)
    {
        $this->geocodeResultsFactory = $geocodeResultsFactory;
    }

    public function parse(array $response) : array
    {
        return $this->geocodeResultsFactory->create($response);
    }

    public function supports(QueryBuilderInterface $queryBuilder) : bool
    {
        return $queryBuilder instanceof GeocodeQueryBuilder;
    }
}
