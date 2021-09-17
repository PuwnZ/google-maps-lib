<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\QueryBuilder;

use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;
use Puwnz\GoogleMapsLib\Geocode\Exception\GeocodeViolationsException;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Language;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\QueryComponents;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Region;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GeocodeQueryBuilder implements QueryBuilderInterface
{
    /** @var string */
    private $address;

    /** @var array */
    private $components = [];

    /** @var ValidatorInterface */
    private $validator;

    /** @var array */
    private $bounds = [];

    /** @var string */
    private $language;

    /** @var string */
    private $region;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    private function buildQueryComponents(): string
    {
        $components = [];

        foreach ($this->getComponents() as $keyComponent => $valueComponent) {
            $components[] = sprintf('%s:%s', $keyComponent, $valueComponent);
        }

        return implode('|', $components);
    }

    private function buildBounds(): string
    {
        $bounds = [];

        foreach ($this->getBounds() as $bound) {
            $bounds[] = implode(',', $bound);
        }

        return implode('|', $bounds);
    }

    public function getQuery(): array
    {
        return [
            'address' => $this->getAddress(),
            'components' => $this->buildQueryComponents(),
            'language' => $this->getLanguage(),
            'bounds' => $this->buildBounds(),
            'region' => $this->getRegion(),
        ];
    }

    private function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): GeocodeQueryBuilder
    {
        $this->address = $address;

        return $this;
    }

    private function getComponents(): array
    {
        return $this->components;
    }

    public function setComponents(array $components): GeocodeQueryBuilder
    {
        $violations = $this->validator->validate($components, [
            new QueryComponents(),
        ]);

        if ($violations->count()) {
            throw new GeocodeViolationsException($violations);
        }

        $this->components = $components;

        return $this;
    }

    private function getBounds(): array
    {
        return $this->bounds;
    }

    public function setBounds(array $bounds): GeocodeQueryBuilder
    {
        $violations = $this->validator->validate($bounds, [
            new Bounds(),
        ]);

        if ($violations->count()) {
            throw new GeocodeViolationsException($violations);
        }

        $this->bounds = $bounds;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): GeocodeQueryBuilder
    {
        $violations = $this->validator->validate($language, [
            new Language(),
        ]);

        if ($violations->count()) {
            throw new GeocodeViolationsException($violations);
        }

        $this->language = $language;

        return $this;
    }

    public function setRegion(string $region): GeocodeQueryBuilder
    {
        $violations = $this->validator->validate($region, [
            new Region(),
        ]);

        if ($violations->count()) {
            throw new GeocodeViolationsException($violations);
        }

        $this->region = $region;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }
}
