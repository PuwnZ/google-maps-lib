<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Validator\Constraints;

use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\QueryComponents;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\QueryComponentsValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class QueryComponentsValidatorTest extends ConstraintValidatorTestCase
{
    public function testValidateNotGoodConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\QueryComponents", "Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds" given');

        $constraint = new Bounds();

        $this->validator->validate([], $constraint);
    }

    public function testValidateNotArray(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected argument of type "array", "string" given');

        $constraint = new QueryComponents();

        $this->validator->validate('', $constraint);
    }

    public function testConstraint(): void
    {
        $constraint = new QueryComponents();

        $components = [
            'mocked-component' => 'failed',
        ];
        $this->validator->validate($components, $constraint);
        $this->buildViolation('Query components key "{{ key }}" not exists.')
            ->setParameter('{{ key }}', 'mocked-component')
            ->assertRaised();
    }

    protected function createValidator(): QueryComponentsValidator
    {
        return new QueryComponentsValidator();
    }
}
