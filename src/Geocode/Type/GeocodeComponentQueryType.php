<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode\Type;

class GeocodeComponentQueryType
{
    public const ROUTE = 'route';
    public const LOCALITY = 'locality';
    public const ADMINISTRATIVE_AREA = 'administrative_area';
    public const POSTAL_CODE = 'postal_code';
    public const COUNTRY = 'country';

    public const TYPES = [
        self::ROUTE,
        self::LOCALITY,
        self::ADMINISTRATIVE_AREA,
        self::POSTAL_CODE,
        self::COUNTRY,
    ];
}
