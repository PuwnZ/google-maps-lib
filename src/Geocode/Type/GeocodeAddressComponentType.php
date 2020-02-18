<?php

declare(strict_types=1);

namespace Puwnz\Google\Geocode\Type;

final class GeocodeAddressComponentType
{
    public const COMPONENT_STREET_ADDRESS = 'street_address';
    public const COMPONENT_STREET_NUMBER = 'street_number';
    public const COMPONENT_ROUTE = 'route';
    public const COMPONENT_INTERSECTION = 'intersection';
    public const COMPONENT_POLITICAL = 'political';
    public const COMPONENT_COUNTRY = 'country';
    public const COMPONENT_ADMINISTRATIVE_AREA_LEVEL_1 = 'administrative_area_level_1';
    public const COMPONENT_ADMINISTRATIVE_AREA_LEVEL_2 = 'administrative_area_level_2';
    public const COMPONENT_ADMINISTRATIVE_AREA_LEVEL_3 = 'administrative_area_level_3';
    public const COMPONENT_ADMINISTRATIVE8AREA_LEVEL_4 = 'administrative_area_level_4';
    public const COMPONENT_ADMINISTRATIVE8AREA_LEVEL_5 = 'administrative_area_level_5';
    public const COMPONENT_COLLOQUIAL_AREA = 'colloquial_area';
    public const COMPONENT_LOCALITY = 'locality';
    public const COMPONENT_WARD = 'ward';
    public const COMPONENT_SUB_LOCALITY = 'sublocality';
    public const COMPONENT_NEIGHBORHOOD = 'neighborhood';
    public const COMPONENT_PREMISE = 'premise';
    public const COMPONENT_SUB_PREMISE = 'subpremise';
    public const COMPONENT_POSTAL_CODE = 'postal_code';
    public const COMPONENT_NATURAL_FEATURE = 'natural_feature';
    public const COMPONENT_AIRPORT = 'airport';
    public const COMPONENT_PARK = 'park';
    public const COMPONENT_POINT_OF_INTEREST = 'point_of_interest';

    public const AVAILABLE_COMPONENTS = [
        self::COMPONENT_STREET_ADDRESS,
        self::COMPONENT_STREET_NUMBER,
        self::COMPONENT_ROUTE,
        self::COMPONENT_INTERSECTION,
        self::COMPONENT_POLITICAL,
        self::COMPONENT_COUNTRY,
        self::COMPONENT_ADMINISTRATIVE_AREA_LEVEL_1,
        self::COMPONENT_ADMINISTRATIVE_AREA_LEVEL_2,
        self::COMPONENT_ADMINISTRATIVE_AREA_LEVEL_3,
        self::COMPONENT_ADMINISTRATIVE8AREA_LEVEL_4,
        self::COMPONENT_ADMINISTRATIVE8AREA_LEVEL_5,
        self::COMPONENT_COLLOQUIAL_AREA,
        self::COMPONENT_LOCALITY,
        self::COMPONENT_WARD,
        self::COMPONENT_SUB_LOCALITY,
        self::COMPONENT_NEIGHBORHOOD,
        self::COMPONENT_PREMISE,
        self::COMPONENT_SUB_PREMISE,
        self::COMPONENT_POSTAL_CODE,
        self::COMPONENT_NATURAL_FEATURE,
        self::COMPONENT_AIRPORT,
        self::COMPONENT_PARK,
        self::COMPONENT_POINT_OF_INTEREST,
    ];
}
