<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Validator\Constraints;

use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeComponentQueryType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class QueryComponentsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof QueryComponents) {
            throw new UnexpectedTypeException($constraint, QueryComponents::class);
        }

        if (\is_array($value) === false) {
            throw new UnexpectedValueException($value, 'array');
        }

        if (\count($value) > 0) {
            array_map([$this, 'checkIsValid'], array_keys($value), [$constraint]);
        }
    }

    private function checkIsValid(string $keyComponent, Constraint $constraint): void
    {
        if (\in_array($keyComponent, GeocodeComponentQueryType::TYPES, true) === false) {
            $this->context->buildViolation($constraint->keyIsWrong)
                ->setParameter('{{ key }}', $keyComponent)
                ->addViolation();
        }
    }
}
