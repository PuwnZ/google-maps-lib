<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Common;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Common\Client\ClientInterface;
use Puwnz\GoogleMapsLib\Common\Exception\ParserException;
use Puwnz\GoogleMapsLib\Common\Parser\ParserInterface;
use Puwnz\GoogleMapsLib\Common\ParserService;
use Puwnz\GoogleMapsLib\Common\QueryBuilder\QueryBuilderInterface;

class ParserServiceTest extends TestCase
{
    /** @var MockObject|ParserInterface */
    private $parserUnsupported;

    /** @var MockObject|ParserInterface */
    private $parserSupported;

    /** @var MockObject|ParserService */
    private $parserService;

    /** @var ParserService */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parserUnsupported = $this->createMock(ParserInterface::class);
        $this->parserSupported = $this->createMock(ParserInterface::class);

        $this->service = new ParserService([
            $this->parserUnsupported,
            $this->parserSupported,
        ]);
    }

    public function testParse(): void
    {
        $queryBuilder = $this->createMock(QueryBuilderInterface::class);
        $client = $this->createMock(ClientInterface::class);

        $this->parserUnsupported->expects(self::once())
            ->method('supports')
            ->with($queryBuilder)
            ->willReturn(false);

        $this->parserSupported->expects(self::once())
            ->method('supports')
            ->with($queryBuilder)
            ->willReturn(true);

        $client->expects(self::once())
            ->method('call')
            ->with($queryBuilder)
            ->willReturn(['mocked']);

        $this->parserSupported->expects(self::once())
            ->method('parse')
            ->with(['mocked'])
            ->willReturn(['mocked']);

        $actual = $this->service->parse($queryBuilder, $client);

        self::assertSame(['mocked'], $actual);
    }

    public function testParseThrow(): void
    {
        $queryBuilder = $this->createMock(QueryBuilderInterface::class);
        $client = $this->createMock(ClientInterface::class);

        $this->parserUnsupported->expects(self::once())
            ->method('supports')
            ->with($queryBuilder)
            ->willReturn(false);

        $this->parserSupported->expects(self::once())
            ->method('supports')
            ->with($queryBuilder)
            ->willReturn(false);

        $this->expectException(ParserException::class);
        $this->expectExceptionMessageMatches('/QueryBuilderInterface/');

        $this->service->parse($queryBuilder, $client);
    }
}
