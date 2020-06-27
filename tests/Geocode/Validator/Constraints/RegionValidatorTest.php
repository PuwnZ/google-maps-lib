<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Validator\Constraints;

use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Region;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\RegionValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class RegionValidatorTest extends ConstraintValidatorTestCase
{
    public function testValidateNotGoodConstraint() : void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Region", "Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds" given');

        $constraint = new Bounds();

        $this->validator->validate([], $constraint);
    }

    public function testValidateNotString() : void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected argument of type "string", "array" given');

        $constraint = new Region();

        $this->validator->validate([], $constraint);
    }

    public function testConstraint() : void
    {
        $constraint = new Region();

        $this->validator->validate('mocked-region', $constraint);
        $this->buildViolation('Region "{{ key }}" is not supported.')
            ->setParameter('{{ key }}', 'mocked-region')
            ->assertRaised();
    }

    protected function createValidator() : RegionValidator
    {
        return new RegionValidator();
    }
}
