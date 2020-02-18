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

use \Puwnz\Google\Geocode\GeocodeFactory;
use Puwnz\Google\Geocode\Type\GeocodeComponentQueryType;


$geocode = GeocodeFactory::create('google-api-key');

$components = [
    GeocodeComponentQueryType::COUNTRY => 'FR'
];

$response = $geocode->getGeocodeResults('10 rue de la Paix, Paris', $components);
```
