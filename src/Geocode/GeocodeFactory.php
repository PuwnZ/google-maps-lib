<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Geocode;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\HttpClient\HttpClient;

class GeocodeFactory
{
    public static function create(string $googleApiKey, string $logFilePath = '/var/logs/geocode.log', float $httpVersion = 2.0): GeocodeParser
    {
        $client = HttpClient::create(['http_version' => $httpVersion]);
        $logger = new Logger('geocode');
        $logger->pushHandler(new StreamHandler($logFilePath, Logger::DEBUG));

        $geocodeClient = new GeocodeClient($client, $logger, $googleApiKey);

        return new GeocodeParser($geocodeClient, new GeocodeResultsFactory());
    }
}
