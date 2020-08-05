<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Common;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Common\Client\ClientInterface;
use Puwnz\GoogleMapsLib\Common\ClientService;
use Puwnz\GoogleMapsLib\Common\Exception\ClientException;
use Puwnz\GoogleMapsLib\Common\ParserService;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;

class ClientServiceTest extends TestCase
{
    /** @var MockObject|ClientInterface */
    private $clientUnsupported;

    /** @var MockObject|ClientInterface */
    private $clientSupported;

    /** @var MockObject|ParserService */
    private $parserService;

    /** @var ClientService */
    private $service;

    protected function setUp() : void
    {
        parent::setUp();

        $this->clientUnsupported = $this->createMock(ClientInterface::class);
        $this->clientSupported = $this->createMock(ClientInterface::class);
        $this->parserService = $this->createMock(ParserService::class);

        $this->service = new ClientService([
            $this->clientUnsupported,
            $this->clientSupported,
        ], $this->parserService);
    }

    public function testCall() : void
    {
        $queryBuilder = $this->createMock(QueryBuilderInterface::class);

        $this->clientUnsupported->expects(static::once())
            ->method('supports')
            ->with($queryBuilder)
            ->willReturn(false);

        $this->clientSupported->expects(static::once())
            ->method('supports')
            ->with($queryBuilder)
            ->willReturn(true);

        $this->parserService->expects(static::once())
            ->method('parse')
            ->with($queryBuilder, $this->clientSupported)
            ->willReturn(['mocked']);

        $actual = $this->service->call($queryBuilder);

        static::assertSame(['mocked'], $actual);
    }

    public function testCallThrow() : void
    {
        $queryBuilder = $this->createMock(QueryBuilderInterface::class);

        $this->clientUnsupported->expects(static::once())
            ->method('supports')
            ->with($queryBuilder)
            ->willReturn(false);

        $this->clientSupported->expects(static::once())
            ->method('supports')
            ->with($queryBuilder)
            ->willReturn(false);

        $this->expectException(ClientException::class);
        $this->expectExceptionMessageMatches('/QueryBuilderInterface/');

        $this->service->call($queryBuilder);
    }
}
