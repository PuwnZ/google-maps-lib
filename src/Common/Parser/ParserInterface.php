<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Common\Parser;

use Puwnz\GoogleMapsLib\Common\QueryBuilder\SupportQueryBuilderInterface;

interface ParserInterface extends SupportQueryBuilderInterface
{
    public function parse(array $response): array;
}
