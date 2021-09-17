<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Common;

use Puwnz\GoogleMapsLib\Common\Client\ClientInterface;
use Puwnz\GoogleMapsLib\Common\Exception\ParserException;
use Puwnz\GoogleMapsLib\Common\Parser\ParserInterface;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;

class ParserService
{
    /** @var ParserInterface[] */
    private $parsers;

    public function __construct(iterable $parsers)
    {
        $this->parsers = $parsers;
    }

    public function parse(QueryBuilderInterface $queryBuilder, ClientInterface $client): array
    {
        foreach ($this->parsers as $parser) {
            if ($parser->supports($queryBuilder)) {
                return $parser->parse($client->call($queryBuilder));
            }
        }

        throw new ParserException(sprintf('Parser for "%s" does not exists', \get_class($queryBuilder)));
    }
}
