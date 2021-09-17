<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\QueryBuilder;

use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Constants\SupportedLanguage;
use Puwnz\GoogleMapsLib\Constants\SupportedRegion;
use Puwnz\GoogleMapsLib\Geocode\Exception\GeocodeViolationsException;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\GeocodeQueryBuilder;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeComponentQueryType;
use Symfony\Component\Validator\Validation;

class GeocodeQueryBuilderTest extends TestCase
{
    /** @var GeocodeQueryBuilder */
    private $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new GeocodeQueryBuilder(Validation::createValidator());
    }

    public function testSetBoundsThrowable(): void
    {
        $this->expectException(GeocodeViolationsException::class);
        $this->expectExceptionMessage('Bounds key "northeast" are not valid.
Bounds key "southwest" are not valid.');

        $this->service->setBounds([
            'northeast' => [],
            'southwest' => [],
        ]);
    }

    public function testSetComponentsThrowable(): void
    {
        $this->expectException(GeocodeViolationsException::class);
        $this->expectExceptionMessage('Query components key "mock-components" not exists.');

        $this->service->setComponents([
            'mock-components' => 'mock-value',
        ]);
    }

    public function testSetLanguageThrowable(): void
    {
        $this->expectException(GeocodeViolationsException::class);
        $this->expectExceptionMessage('Language "mock-language" is not supported.');

        $this->service->setLanguage('mock-language');
    }

    public function testSetRegionThrowable(): void
    {
        $this->expectException(GeocodeViolationsException::class);
        $this->expectExceptionMessage('Region "mock-region" is not supported.');

        $this->service->setRegion('mock-region');
    }

    public function testGetQuery(): void
    {
        $components = [
            GeocodeComponentQueryType::COUNTRY => 'FR',
        ];

        $this->service->setAddress('10 rue de la Paix, Paris')
            ->setComponents($components)
            ->setLanguage(SupportedLanguage::FRENCH)
            ->setRegion(SupportedRegion::CA)
            ->setBounds([
                'northeast' => [
                    'lat' => 0.0,
                    'lng' => 1.0,
                ],
                'southwest' => [
                    'lat' => -0.0,
                    'lng' => -1.0,
                ],
            ]);

        $actual = $this->service->getQuery();

        $expected = [
            'address' => '10 rue de la Paix, Paris',
            'components' => 'country:FR',
            'bounds' => '0,1|-0,-1',
            'language' => 'fr',
            'region' => 'ca',
        ];

        self::assertEquals($expected, $actual);
    }
}
