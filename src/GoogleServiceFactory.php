<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Puwnz\GoogleMapsLib\Common\ClientService;
use Puwnz\GoogleMapsLib\Common\ParserService;
use Puwnz\GoogleMapsLib\Geocode\Client\GeocodeClient;
use Puwnz\GoogleMapsLib\Geocode\Parser\GeocodeParser;
use Puwnz\GoogleMapsLib\Geocode\Parser\GeocodeResultsFactory;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpClient\HttpClient;

class GoogleServiceFactory
{
    public static function create(string $googleApiKey, string $logFilePath = './var/logs/geocode.log', float $httpVersion = 2.): GoogleService
    {
        $logger = new Logger('geocode');
        $logger->pushHandler(new StreamHandler($logFilePath, Logger::DEBUG));

        $client = HttpClient::create(['http_version' => $httpVersion]);

        $cache = new FilesystemAdapter('google-geocode', 0, './var/cache');

        $geocodeResultsFactory = new GeocodeResultsFactory($logger);

        $parsers = [
            new GeocodeParser($geocodeResultsFactory),
        ];

        $clients = [
            new GeocodeClient($client, $logger, $cache, $googleApiKey),
        ];

        $parserService = new ParserService($parsers);

        $clientService = new ClientService($clients, $parserService);

        return new GoogleService($clientService, $logger);
    }
}
