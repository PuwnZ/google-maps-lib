<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Validator\Constraints;

use Puwnz\GoogleMapsLib\Constants\SupportedRegion;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class RegionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint) : void
    {
        if (!$constraint instanceof Region) {
            throw new UnexpectedTypeException($constraint, Region::class);
        }

        if (\is_string($value) === false) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!\in_array($value, SupportedRegion::ALL, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ key }}', $value)
                ->addViolation();
        }
    }
}
