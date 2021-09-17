<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Puwnz\GoogleMapsLib\Common\ClientService;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;
use Puwnz\GoogleMapsLib\GoogleService;

class GoogleServiceTest extends TestCase
{
    /** @var MockObject|ClientService */
    private $clientService;

    /** @var MockObject|LoggerInterface */
    private $logger;

    /** @var GoogleService */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->clientService = $this->createMock(ClientService::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = new GoogleService($this->clientService, $this->logger);
    }

    public function testApply(): void
    {
        $queryBuilder = $this->createMock(QueryBuilderInterface::class);

        $this->clientService->expects(self::once())
            ->method('call')
            ->with($queryBuilder)
            ->willReturn(['mocked']);

        $actual = $this->service->apply($queryBuilder);

        self::assertSame(['mocked'], $actual);
    }

    public function testApplyThrow(): void
    {
        $queryBuilder = $this->createMock(QueryBuilderInterface::class);

        $this->clientService->expects(self::once())
            ->method('call')
            ->willThrowException(new \Exception('mocked exception'));

        $this->logger->expects(self::once())
            ->method('error')
            ->with('mocked exception', ['query_builder' => \get_class($queryBuilder)]);

        $this->service->apply($queryBuilder);
    }
}
