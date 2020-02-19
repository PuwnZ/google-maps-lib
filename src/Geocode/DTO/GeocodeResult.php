<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\DTO;

class GeocodeResult
{
    /** @var GeocodeAddressComponent[] */
    private $addressComponents;

    /** @var string */
    private $formattedAddress;

    /** @var string[] */
    private $types;

    /** @var string */
    private $placeId;

    /** @var bool */
    private $partialMatch;

    /** @var GeocodeGeometry */
    private $geometry;

    public function setGeocodeAddressComponent(GeocodeAddressComponent ...$geocodeAddressComponents) : self
    {
        $this->addressComponents = $geocodeAddressComponents;

        return $this;
    }

    public function getGeocodeAddressComponents() : array
    {
        return $this->addressComponents;
    }

    public function setFormattedAddress(string $formattedAddress) : self
    {
        $this->formattedAddress = $formattedAddress;

        return $this;
    }

    public function getFormattedAddress() : string
    {
        return $this->formattedAddress;
    }

    public function setTypes(string ...$types) : self
    {
        $this->types = $types;

        return $this;
    }

    public function getTypes() : array
    {
        return $this->types;
    }

    public function setPlaceId(string $placeId) : self
    {
        $this->placeId = $placeId;

        return $this;
    }

    public function getPlaceId() : string
    {
        return $this->placeId;
    }

    public function setPartialMatch(bool $partialMatch) : self
    {
        $this->partialMatch = $partialMatch;

        return $this;
    }

    public function isPartialMatch() : bool
    {
        return $this->partialMatch;
    }

    public function setGeometry(GeocodeGeometry $geometry) : self
    {
        $this->geometry = $geometry;

        return $this;
    }

    public function getGeometry() : GeocodeGeometry
    {
        return $this->geometry;
    }
}
