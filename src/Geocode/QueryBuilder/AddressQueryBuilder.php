<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\QueryBuilder;

use Puwnz\GoogleMapsLib\Geocode\Exception\GeocodeComponentQueryException;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeComponentQueryType;

class AddressQueryBuilder implements QueryBuilderInterface
{
    /** @var string */
    private $address;

    /** @var array */
    private $components;

    public function __construct(string $address, array $components = [])
    {
        $this->address = $address;
        $this->components = $components;
    }

    /**
     * @throws GeocodeComponentQueryException
     */
    private function buildQueryComponents() : string
    {
        $components = [];

        foreach ($this->components as $keyComponent => $valueComponent) {
            if (!\in_array($keyComponent, GeocodeComponentQueryType::TYPES)) {
                throw new GeocodeComponentQueryException($keyComponent);
            }

            $components[] = \sprintf('%s:%s', $keyComponent, $valueComponent);
        }

        return \implode('|', $components);
    }

    public function getQuery() : array
    {
        return [
            'address' => $this->address,
            'components' => $this->buildQueryComponents(),
        ];
    }
}
