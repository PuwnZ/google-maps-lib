<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\DTO;

class GeocodeAddressComponent
{
    /** @var string[] */
    private $types;

    /** @var string */
    private $longName;

    /** @var string */
    private $shortName;

    public function setTypes(string ...$types): self
    {
        $this->types = $types;

        return $this;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function setLongName(string $longName): self
    {
        $this->longName = $longName;

        return $this;
    }

    public function getLongName(): string
    {
        return $this->longName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }
}
