<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib;

use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Common\ClientService;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\GeocodeQueryBuilder;

class GoogleService
{
    /** @var ClientService */
    private $clientService;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(ClientService $clientService, LoggerInterface $logger)
    {
        $this->clientService = $clientService;
        $this->logger = $logger;
    }

    /**
     * @param GeocodeQueryBuilder $queryBuilder
     */
    public function apply(QueryBuilderInterface $queryBuilder): array
    {
        try {
            return $this->clientService->call($queryBuilder);
        } catch (\Throwable $e) {
            $this->logger->error(
                $e->getMessage(),
                [
                    'query_builder' => \get_class($queryBuilder),
                ]
            );

            return [];
        }
    }
}
