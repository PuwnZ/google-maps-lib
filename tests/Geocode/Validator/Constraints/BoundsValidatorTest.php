<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Validator\Constraints;

use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\BoundsValidator;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\QueryComponents;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class BoundsValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator() : BoundsValidator
    {
        return new BoundsValidator();
    }

    public function testValidateNotGoodConstraint() : void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds", "Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\QueryComponents" given');

        $constraint = new QueryComponents();

        $this->validator->validate([], $constraint);
    }

    public function testValidateNotArray() : void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected argument of type "array", "string" given');

        $constraint = new Bounds();

        $this->validator->validate('', $constraint);
    }

    public function testValidateNortheastOrSouthwestMissing() : void
    {
        $constraint = new Bounds();

        $this->validator->validate(['mocked-northeast' => [], 'mocked-southwest' => []], $constraint);

        $this->buildViolation('Bounds key "{{ key }}" not exists.')
            ->setParameter('{{ key }}', 'northeast')
            ->buildNextViolation('Bounds key "{{ key }}" not exists.')
            ->setParameter('{{ key }}', 'southwest')
            ->assertRaised();
    }

    public function testValidateNortheastNotArray() : void
    {
        $constraint = new Bounds();

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected argument of type "array", "string" given');

        $this->validator->validate(['northeast' => '', 'southwest' => ''], $constraint);
    }

    public function testValidateSouthwestNotArray() : void
    {
        $constraint = new Bounds();

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected argument of type "array", "string" given');

        $this->validator->validate(['northeast' => [], 'southwest' => ''], $constraint);
    }

    public function testValidateNortheastHasNotTwoKeys() : void
    {
        $constraint = new Bounds();

        $this->validator->validate(['northeast' => [], 'southwest' => []], $constraint);

        $this->buildViolation('Bounds key "{{ key }}" are not valid.')
            ->setParameter('{{ key }}', 'northeast')
            ->buildNextViolation('Bounds key "{{ key }}" are not valid.')
            ->setParameter('{{ key }}', 'southwest')
            ->assertRaised();
    }

    public function testValidateBoundsHasNotLngAndLat() : void
    {
        $constraint = new Bounds();

        $this->validator->validate([
            'northeast' => [
                'mocked-lat' => '',
                'mocked-lng' => '',
            ],
            'southwest' => [
                'mocked-lat' => '',
                'mocked-lng' => '',
            ],
        ], $constraint);

        $this->buildViolation('Bounds key "{{ key }}" not exists.')
            ->setParameter('{{ key }}', 'northeast.lat')
            ->buildNextViolation('Bounds key "{{ key }}" not exists.')
            ->setParameter('{{ key }}', 'northeast.lng')
            ->buildNextViolation('Bounds key "{{ key }}" not exists.')
            ->setParameter('{{ key }}', 'southwest.lat')
            ->buildNextViolation('Bounds key "{{ key }}" not exists.')
            ->setParameter('{{ key }}', 'southwest.lng')
            ->assertRaised();
    }
}
