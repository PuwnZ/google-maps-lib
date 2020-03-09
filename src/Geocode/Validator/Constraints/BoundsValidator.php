<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Validator\Constraints;

use Puwnz\GoogleMapsLib\Geocode\Exception\GeocodeBoundsDataException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BoundsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint) : void
    {
        if (!$constraint instanceof Bounds) {
            throw new UnexpectedTypeException($constraint, Bounds::class);
        }

        if (\is_array($value) === false) {
            throw new \UnexpectedValueException($value, 'array');
        }

        $this->eligibleData($value, $constraint);
    }

    private function eligibleData($value, Constraint $constraint) : void
    {
        $this->keyExists('northeast', $value, $constraint);
        $this->keyExists('southwest', $value, $constraint);

        if (!\is_array($value['northeast']) || !\is_array($value['southwest'])) {
            throw new GeocodeBoundsDataException();
        }

        if (\count($value['northeast']) !== 2) {
            $this->context->buildViolation($constraint->messageBoundsCounter)
                ->setParameter('{{ key }}', 'northeast')
                ->addViolation();
        }

        if (\count($value['southwest']) !== 2) {
            $this->context->buildViolation($constraint->messageBoundsCounter)
                ->setParameter('{{ key }}', 'southwest')
                ->addViolation();
        }

        $this->keyExists('lat', $value['northeast'], $constraint);
        $this->keyExists('lng', $value['northeast'], $constraint);

        $this->keyExists('lat', $value['southwest'], $constraint);
        $this->keyExists('lng', $value['southwest'], $constraint);
    }

    private function keyExists($key, $value, Constraint $constraint) : void
    {
        if (!\array_key_exists($key, $value)) {
            $this->context->buildViolation($constraint->messageKeyNotExists)
                ->setParameter('{{ key }}', $key)
                ->addViolation();
        }
    }
}
