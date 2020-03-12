<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\Validator\Constraints;

use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Language;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\LanguageValidator;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\QueryComponents;
use Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\QueryComponentsValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class LanguageValidatorTest extends ConstraintValidatorTestCase
{
    public function testValidateNotGoodConstraint() : void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessage('Expected argument of type "Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Language", "Puwnz\GoogleMapsLib\Geocode\Validator\Constraints\Bounds" given');

        $constraint = new Bounds();

        $this->validator->validate([], $constraint);
    }

    public function testValidateNotString() : void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Expected argument of type "string", "array" given');

        $constraint = new Language();

        $this->validator->validate([], $constraint);
    }

    public function testConstraint() : void
    {
        $constraint = new Language();

        $this->validator->validate('mocked-language', $constraint);
        $this->buildViolation('Language "{{ key }}" is not supported.')
            ->setParameter('{{ key }}', 'mocked-language')
            ->assertRaised();
    }

    protected function createValidator() : LanguageValidator
    {
        return new LanguageValidator();
    }
}
