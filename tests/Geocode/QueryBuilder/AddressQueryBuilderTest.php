<?php

declare(strict_types=1);

namespace Puwnz\GoogleMapsLib\Tests\Geocode\QueryBuilder;

use PHPUnit\Framework\TestCase;
use Puwnz\GoogleMapsLib\Constants\SupportedLanguage;
use Puwnz\GoogleMapsLib\Geocode\Exception\GeocodeViolationsException;
use Puwnz\GoogleMapsLib\Geocode\QueryBuilder\AddressQueryBuilder;
use Puwnz\GoogleMapsLib\Geocode\Type\GeocodeComponentQueryType;
use Symfony\Component\Validator\Validation;

class AddressQueryBuilderTest extends TestCase
{
    /** @var AddressQueryBuilder */
    private $service;

    protected function setUp() : void
    {
        parent::setUp();

        $this->service = new AddressQueryBuilder(Validation::createValidator());
    }

    public function testSetBoundsThrowable() : void
    {
        $this->expectException(GeocodeViolationsException::class);
        $this->expectExceptionMessage('Bounds key "northeast" are not valid.
Bounds key "southwest" are not valid.');

        $this->service->setBounds([
            'northeast' => [],
            'southwest' => [],
        ]);
    }

    public function testSetComponentsThrowable() : void
    {
        $this->expectException(GeocodeViolationsException::class);
        $this->expectExceptionMessage('Query components key "mock-components" not exists.');

        $this->service->setComponents([
            'mock-components' => 'mock-value',
        ]);
    }

    public function testGetQuery() : void
    {
        $components = [
            GeocodeComponentQueryType::COUNTRY => 'FR',
        ];

        $this->service->setAddress('10 rue de la Paix, Paris')
            ->setComponents($components)
            ->setLanguage(SupportedLanguage::FRENCH)
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
        ];

        static::assertEquals($expected, $actual);
    }
}
