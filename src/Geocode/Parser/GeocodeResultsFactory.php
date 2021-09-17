<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Parser;

use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeAddressComponent;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeGeometry;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeResult;
use Puwnz\GoogleMapsLib\Geocode\DTO\Geometry\GeometryLocation;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeAddressComponentType;

class GeocodeResultsFactory
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return GeocodeResult[]
     */
    public function create(array $response): array
    {
        $results = [];

        if ($response['results'] === []) {
            return $this->checkResponseIsError($response);
        }

        foreach ($response['results'] as $address) {
            $results[] = $this->buildGeocodeResult($address);
        }

        return $results;
    }

    private function buildGeocodeResult(array $address): ?GeocodeResult
    {
        return (new GeocodeResult())
            ->setGeocodeAddressComponent(...$this->setGeocodeAddressComponent($address['address_components']))
            ->setGeometry($this->createGeometry($address['geometry']))
            ->setFormattedAddress($address['formatted_address'])
            ->setPlaceId($address['place_id'])
            ->setPartialMatch($address['partial_match'] ?? false)
            ->setTypes(...$address['types']);
    }

    private function setGeocodeAddressComponent(array $addressComponents): array
    {
        $components = array_map([$this, 'createAddressComponent'], $addressComponents);

        return array_filter($components, function (?GeocodeAddressComponent $component) { return $component !== null; });
    }

    private function createAddressComponent(array $addressComponent): GeocodeAddressComponent
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

    private function createGeometry(array $geometry): GeocodeGeometry
    {
        $geocodeGeometry = new GeocodeGeometry();

        $location = (new GeometryLocation())
            ->setLatitude($geometry['location']['lat'])
            ->setLongitude($geometry['location']['lng']);

        $geocodeGeometry->setLocation($location);

        return $geocodeGeometry;
    }

    private function checkResponseIsError(array $response): array
    {
        if (\array_key_exists('status', $response) && \array_key_exists('error_message', $response)) {
            $this->logger->error($response['status'], [$response['error_message']]);
        }

        return [];
    }
}
