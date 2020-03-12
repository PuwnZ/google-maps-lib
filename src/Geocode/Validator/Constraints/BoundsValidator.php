<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BoundsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint) : void
    {
        if (!$constraint instanceof Bounds) {
            throw new UnexpectedTypeException($constraint, Bounds::class);
        }

        if (\is_array($value) === false) {
            throw new UnexpectedValueException($value, 'array');
        }

        $this->eligibleData($value, $constraint);
    }

    private function eligibleData($value, Constraint $constraint) : void
    {
        $this->keyExists('northeast', $value, $constraint);
        $this->keyExists('southwest', $value, $constraint);

        if ($this->context->getViolations()->count() > 0) {
            return;
        }

        if (\is_array($value['northeast']) === false) {
            throw new UnexpectedValueException($value['northeast'], 'array');
        }

        if (\is_array($value['southwest']) === false) {
            throw new UnexpectedValueException($value['southwest'], 'array');
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

        if ($this->context->getViolations()->count() > 0) {
            return;
        }

        $this->keyExists('lat', $value['northeast'], $constraint, 'northeast.lat');
        $this->keyExists('lng', $value['northeast'], $constraint, 'northeast.lng');

        $this->keyExists('lat', $value['southwest'], $constraint, 'southwest.lat');
        $this->keyExists('lng', $value['southwest'], $constraint, 'southwest.lng');
    }

    private function keyExists($key, $value, Constraint $constraint, string $varKey = null) : void
    {
        if (!\array_key_exists($key, $value)) {
            $this->context->buildViolation($constraint->messageKeyNotExists)
                ->setParameter('{{ key }}', $varKey ?? $key)
                ->addViolation();
        }
    }
}
