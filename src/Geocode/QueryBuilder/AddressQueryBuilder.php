<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\QueryBuilder;

use Puwnz\GoogleMapsLib\Geocode\Exception\GeocodeViolationsException;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\QueryComponents;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddressQueryBuilder implements QueryBuilderInterface
{
    /** @var string */
    private $address;

    /** @var array */
    private $components;

    /** @var ValidatorInterface */
    private $validator;

    /** @var array */
    private $bounds;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    private function buildQueryComponents() : string
    {
        $components = [];

        foreach ($this->getComponents() as $keyComponent => $valueComponent) {
            $components[] = \sprintf('%s:%s', $keyComponent, $valueComponent);
        }

        return \implode('|', $components);
    }

    private function buildBounds() : string
    {
        $bounds = [];

        foreach ($this->getBounds() as $bound) {
            $bounds[] = \implode(',', $bound);
        }

        return \implode('|', $bounds);
    }

    public function getQuery() : array
    {
        return [
            'address' => $this->getAddress(),
            'components' => $this->buildQueryComponents(),
            'bounds' => $this->buildBounds(),
        ];
    }

    private function getAddress() : string
    {
        return $this->address;
    }

    public function setAddress(string $address) : self
    {
        $this->address = $address;

        return $this;
    }

    private function getComponents() : array
    {
        return $this->components;
    }

    public function setComponents(array $components) : AddressQueryBuilder
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

    private function getBounds() : array
    {
        return $this->bounds;
    }

    public function setBounds(array $bounds) : AddressQueryBuilder
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
}
