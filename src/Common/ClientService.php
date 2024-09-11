<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Common;

use Puwnz\GoogleMapsLib\Common\Client\ClientInterface;
use Puwnz\GoogleMapsLib\Common\Exception\ClientException;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;

class ClientService
{
    /** @var ClientInterface[] */
    private $clients;

    /** @var ParserService */
    private $parserService;

    public function __construct(iterable $clients, ParserService $parserService)
    {
        $this->clients = $clients;
        $this->parserService = $parserService;
    }

    public function call(QueryBuilderInterface $queryBuilder): array
    {
        foreach ($this->clients as $client) {
            if ($client->supports($queryBuilder)) {
                return $this->parserService->parse($queryBuilder, $client);
            }
        }

        throw new ClientException(\sprintf('Client for "%s" does not exists', \get_class($queryBuilder)));
    }
}
