<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Parser;

use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeAddressComponent;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeGeometry;
use Puwnz\GoogleMapsLib\Geocode\DTO\GeocodeResult;
use Puwnz\GoogleMapsLib\Geocode\DTO\Geometry\GeometryLocation;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeAddressComponentType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    private function buildGeocodeResult(array $address): GeocodeResult
    {
        $serializer = $this->getSerializer();

        $geometry = $this->createGeometry($address['geometry']);

        unset($address['geometry']);

        /** @var GeocodeResult $result */
        $result = $serializer->deserialize(json_encode($address), GeocodeResult::class, 'json');
        $result->setGeometry($geometry);
        $result->setGeocodeAddressComponent(...$this->setGeocodeAddressComponent($address['address_components']));

        return $result;
    }

    private function setGeocodeAddressComponent(array $addressComponents): array
    {
        $components = array_map([$this, 'createAddressComponent'], $addressComponents);

        return array_filter($components, function (GeocodeAddressComponent $component) { return $component !== null; });
    }

    public function getSerializer(): Serializer
    {
        $normalizer = [new ObjectNormalizer()];

        return new Serializer($normalizer, [new JsonEncoder()]);
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

        if ($geocodeAddressComponent === null) {
            $this->logger->error('Component not working', ['address_component' => $addressComponent]);
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
