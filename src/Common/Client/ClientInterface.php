<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Common\Client;

use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\SupportQueryBuilderInterface;

interface ClientInterface extends SupportQueryBuilderInterface
{
    public function call(QueryBuilderInterface $queryBuilder) : array;
}
