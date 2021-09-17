<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Common\QueryBuilder;

interface QueryBuilderInterface
{
    /**
     * Create http-client query.
     */
    public function getQuery(): array;
}
