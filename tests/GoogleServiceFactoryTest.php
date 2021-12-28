<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode;

use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\GoogleService;
use Puwnz\GoogleMapsLib\GoogleServiceFactory;

class GoogleServiceFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $actual = GoogleServiceFactory::create('google-api-key', './var/logs/geocode.log', 2.);

        self::assertInstanceOf(GoogleService::class, $actual);
    }
}
