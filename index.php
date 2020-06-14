<?php

require './vendor/autoload.php';

use Puwnz\GoogleMapsLib\Constants\SupportedLanguage;
use Puwnz\GoogleMapsLib\Geocode\GeocodeFactory;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\AddressQueryBuilder;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeComponentQueryType;
use Symfony\Component\Validator\Validation;

$geocode = GeocodeFactory::create('google-api-key', 'path/log/file', 2.0);

$components = [
    GeocodeComponentQueryType::COUNTRY => 'FR'
];

$validator = Validation::createValidator();
$addressBuilder = new AddressQueryBuilder($validator);

$addressBuilder->setAddress('10 rue de la Paix, Paris')
    ->setComponents($components)
    ->setLanguage(SupportedLanguage::FRENCH)
    ->setBounds([
        'northeast' => [
            'lat' => 0.0,
            'lng' => 1.0
        ],
        'southwest' => [
            'lat' => -0.0,
            'lng' => -1.0
        ]
    ]);
