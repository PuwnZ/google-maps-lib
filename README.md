# Readme

## Overview

The Google Lib project provides a Google Map integration for you PHP 7.3+ project. At this time, just geocode is enable in this lib, because my needs is only on this part, but you can open [issues](/issues) to push your needs.

## Installation

To install this lib you can just use composer :

```
composer require puwnz/google-maps-lib
```

## Integration

```php
<?php

use Puwnz\GoogleMapsLib\Constants\SupportedLanguage;
use Puwnz\GoogleMapsLib\Constants\SupportedRegion;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\GeocodeQueryBuilder;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeComponentQueryType;
use Puwnz\GoogleMapsLib\GoogleServiceFactory;
use Symfony\Component\Validator\Validation;

$geocode = GoogleServiceFactory::create('google-api-key', 'path/log/file', 'http-version');

$components = [
    GeocodeComponentQueryType::COUNTRY => 'FR'
];

$geocodeQueryBuilder = new GeocodeQueryBuilder(Validation::createValidator());

$geocodeQueryBuilder->setAddress('10 rue de la Paix, Paris')
    ->setComponents($components)
    ->setLanguage(SupportedLanguage::FRENCH)
    ->setRegion(SupportedRegion::FR)
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

$response = $geocode->apply($geocodeQueryBuilder);
```
The first parameter of factory is required, but the path file for a log and http-version are not.

> `http-version` should be a float.

## Language supported

Google does not accept all language for their apis. You can found all language supported [here](https://developers.google.com/maps/faq#languagesupport)

> If you don't set the language, google can return partial geocoding response for example.

## Testing

The bundle is fully unit tested by [PHPUnit](http://www.phpunit.de/) with a code coverage close to **100%**.

## Exception

Some exceptions are trigger in this lib, the next list explain in few words why :

- *\Puwnz\GoogleMapsLib\Geocode\Exception\GeocodeViolationsException* is trigger when you set a wrong data on some builder.
For example, if you not set `lat` key on `bounds` in `\Puwnz\GoogleMapsLib\Geocode\QueryBuilder\AddressQueryBuilder`  

## Contribute

We love contributors! This is an open source project. If you'd like to contribute, feel free to propose a PR!

## License

The Google Map Lib is under the MIT license. For the full copyright and license information, please read the
[LICENSE](/LICENSE) file that was distributed with this source code.
