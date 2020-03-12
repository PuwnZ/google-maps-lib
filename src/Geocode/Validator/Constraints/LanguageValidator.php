<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class LanguageValidator extends ConstraintValidator
{
    private const SUPPORTED_LANGUAGES = [
        'af',
        'ja',
        'sq',
        'kn',
        'am',
        'kk',
        'ar',
        'km',
        'hy',
        'ko',
        'az',
        'ky',
        'eu',
        'lo',
        'be',
        'lv',
        'bn',
        'lt',
        'bs',
        'mk',
        'bg',
        'ms',
        'my',
        'ml',
        'ca',
        'mr',
        'zh',
        'mn',
        'zh-CN',
        'ne',
        'zh-HK',
        'no',
        'zh-TW',
        'pl',
        'hr',
        'pt',
        'cs',
        'pt-BR',
        'da',
        'pt-PT',
        'nl',
        'pa',
        'en',
        'ro',
        'en-AU',
        'ru',
        'en-GB',
        'sr',
        'et',
        'si',
        'fa',
        'sk',
        'fi',
        'sl',
        'fil',
        'es',
        'fr',
        'es-419',
        'fr-CA',
        'sw',
        'gl',
        'sv',
        'ka',
        'ta',
        'de',
        'te',
        'el',
        'th',
        'gu',
        'tr',
        'iw',
        'uk',
        'hi',
        'ur',
        'hu',
        'uz',
        'is',
        'vi',
        'id',
        'zu',
        'it',
    ];

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Language) {
            throw new UnexpectedTypeException($constraint, Language::class);
        }

        if (\is_string($value) === false) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!\in_array($value, self::SUPPORTED_LANGUAGES, true)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ key }}', $value)
                ->addViolation();
        }
    }
}
