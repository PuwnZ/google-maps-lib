<?php

declare(strict_types=1);

namespace Puwnz\Google\Geocode;

use Puwnz\Google\Geocode\DTO\GeocodeAddressComponent;
use Puwnz\Google\Geocode\DTO\GeocodeGeometry;
use Puwnz\Google\Geocode\DTO\GeocodeResult;
use Puwnz\Google\Geocode\DTO\Geometry\GeometryLocation;
use Puwnz\Google\Geocode\Type\GeocodeAddressComponentType;

class GeocodeResultsFactory
{
    /**
     * @return GeocodeResult[]
     */
    public function create(array $response) : array
    {
        $results = [];

        if (empty($response['results'])) {
            return $results;
        }

        foreach ($response['results'] as $address) {
            $results[] = $this->buildGeocodeResult($address);
        }

        return $results;
    }

    private function buildGeocodeResult(array $address) : ?GeocodeResult
    {
        return (new GeocodeResult())
            ->setGeocodeAddressComponent(...$this->setGeocodeAddressComponent($address['address_components']))
            ->setGeometry($this->createGeometry($address['geometry']))
            ->setFormattedAddress($address['formatted_address'])
            ->setPlaceId($address['place_id'])
            ->setPartialMatch($address['partial_match'] ?? false)
            ->setTypes(...$address['types']);
    }

    private function setGeocodeAddressComponent(array $addressComponents) : array
    {
        $components = \array_map([$this, 'createAddressComponent'], $addressComponents);

        return \array_filter($components, function (?GeocodeAddressComponent $component) { return $component !== null; });
    }

    private function createAddressComponent(array $addressComponent) : GeocodeAddressComponent
    {
        $geocodeAddressComponent = null;

        foreach (GeocodeAddressComponentType::AVAILABLE_COMPONENTS as $componentType) {
            if (!\in_array($componentType, $addressComponent['types'], true)) {
                continue;
            }

            $geocodeAddressComponent = (new GeocodeAddressComponent())
                ->setTypes(...$addressComponent['types'])
                ->setLongName($addressComponent['long_name'])
                ->setShortName($addressComponent['short_name']);

            break;
        }

        return $geocodeAddressComponent;
    }

    private function createGeometry(array $geometry) : GeocodeGeometry
    {
        $geocodeGeometry = new GeocodeGeometry();

        $location = (new GeometryLocation())
            ->setLatitude($geometry['location']['lat'])
            ->setLongitude($geometry['location']['lng']);

        $geocodeGeometry->setLocation($location);

        return $geocodeGeometry;
    }
}
