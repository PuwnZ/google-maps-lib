# Readme

## Overview

The Google Lib project provides a Google Map integration for you PHP 7.3+ project. At this time, just geocode is enable in this lib, because my needs is only on this part, but you can open [issues](/issues) to push your needs.

## Installation

To install this lib you can just use composer :

```
composer require puwnz/google-lib
```

## Integration

```php
<?php

use Puwnz\GoogleMapsLib\Geocode\GeocodeFactory;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeComponentQueryType;


$geocode = GeocodeFactory::create('google-api-key');

$components = [
    GeocodeComponentQueryType::COUNTRY => 'FR'
];

$response = $geocode->getGeocodeResults('10 rue de la Paix, Paris', $components);
```

## Testing

The bundle is fully unit tested by [PHPUnit](http://www.phpunit.de/) with a code coverage close to **100%**.

## Contribute

We love contributors! This is an open source project. If you'd like to contribute, feel free to propose a PR!

## License

The Google Map Lib is under the MIT license. For the full copyright and license information, please read the
[LICENSE](/LICENSE) file that was distributed with this source code.
