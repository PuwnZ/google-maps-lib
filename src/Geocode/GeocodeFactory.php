<?php

declare(strict_types=1);

namespace Puwnz\Google\Geocode;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\HttpClient\HttpClient;

class GeocodeFactory
{
    public static function create(string $googleApiKey, string $logFilePath = '/var/logs/geocode.log') : GeocodeParser
    {
        $client = HttpClient::create();
        $logger = new Logger('geocode');
        $logger->pushHandler(new StreamHandler($logFilePath, Logger::DEBUG));

        $geocodeClient = new GeocodeClient($client, $logger, $googleApiKey);

        return new GeocodeParser($geocodeClient, new GeocodeResultsFactory());
    }
}
