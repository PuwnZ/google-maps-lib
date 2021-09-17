<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Common\QueryBuilder;

interface SupportQueryBuilderInterface
{
    public function supports(QueryBuilderInterface $queryBuilder): bool;
}
