<?php

/*
 * Translates a number to a short alphanumeric version
 *
 * @author Kevin van Zonneveld <kevin@transloadit.com> (https://github.com/kvz)
 * @author Simon Franz
 * @author Deadfish
 * @author SK83RJOSH
 * @author Alexey Samara <lion.samara@gmail.com> (https://wow-apps.pro)
 * @copyright 2008 Kevin van Zonneveld.
 */

namespace Kvz\YoutubeId\Tests;

use Kvz\YoutubeId\Converter;
use Kvz\YoutubeId\Exception\ConverterException;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    /** @var array */
    private $testData = [
        'simple'       => [
            'alphanumeric' => 'C7nXQpS',
            'numeric'      => 2188847690240,
        ],
        'padUp'        => [
            'alphanumeric' => 'C7nXQpS',
            'numeric'      => 2188847686396,
            'padUp'        => 3,
        ],
        'secured'      => [
            'alphanumeric' => 'ccBAIloC70',
            'numeric'      => 261383993199003048,
            'secureKey'    => 'Shfu388291ssD',
        ],
        'padUpSecured' => [
            'alphanumeric' => 'C7nXQpS',
            'numeric'      => 1327301435881,
            'padUp'        => 3,
            'secureKey'    => 'Shfu388291ssD',
        ],
    ];

    public function testToNumeric()
    {
        $this->assertTrue(
            is_int(Converter::toNumeric($this->testData['simple']['alphanumeric']))
        );

        $this->assertSame(
            $this->testData['simple']['numeric'],
            Converter::toNumeric($this->testData['simple']['alphanumeric'])
        );
    }

    public function testToNumericWithPadUp()
    {
        $this->assertSame(
            $this->testData['padUp']['numeric'],
            Converter::toNumeric($this->testData['padUp']['alphanumeric'], $this->testData['padUp']['padUp'])
        );
    }

    public function testToNumericSecured()
    {
        $this->assertSame(
            $this->testData['secured']['numeric'],
            Converter::toNumeric(
                $this->testData['secured']['alphanumeric'],
                0,
                $this->testData['secured']['secureKey']
            )
        );
    }

    public function testToNumericWithPadUpSecured()
    {
        $this->assertSame(
            $this->testData['padUpSecured']['numeric'],
            Converter::toNumeric(
                $this->testData['padUpSecured']['alphanumeric'],
                $this->testData['padUpSecured']['padUp'],
                $this->testData['padUpSecured']['secureKey']
            )
        );
    }

    public function testToAlphanumeric()
    {
        $this->assertTrue(
            is_string(Converter::toAlphanumeric($this->testData['simple']['numeric']))
        );

        $this->assertSame(
            $this->testData['simple']['alphanumeric'],
            Converter::toAlphanumeric($this->testData['simple']['numeric'])
        );
    }

    public function testToAlphanumericWithPadUp()
    {
        $this->assertSame(
            $this->testData['padUp']['alphanumeric'],
            Converter::toAlphanumeric($this->testData['padUp']['numeric'], $this->testData['padUp']['padUp'])
        );
    }

    public function testToAlphanumericSecured()
    {
        $this->assertSame(
            $this->testData['secured']['alphanumeric'],
            Converter::toAlphanumeric(
                $this->testData['secured']['numeric'],
                0,
                $this->testData['secured']['secureKey']
            )
        );
    }

    public function testToAlphanumericWithPadUpSecured()
    {
        $this->assertSame(
            $this->testData['padUpSecured']['alphanumeric'],
            Converter::toAlphanumeric(
                $this->testData['padUpSecured']['numeric'],
                $this->testData['padUpSecured']['padUp'],
                $this->testData['padUpSecured']['secureKey']
            )
        );
    }

    public function testToAlphanumericWithTransform()
    {
        $this->assertSame(
            $this->testData['simple']['alphanumeric'],
            Converter::toAlphanumeric($this->testData['simple']['numeric'], 0, null, Converter::TRANSFORM_NONE)
        );

        $this->assertSame(
            strtoupper($this->testData['simple']['alphanumeric']),
            Converter::toAlphanumeric($this->testData['simple']['numeric'], 0, null, Converter::TRANSFORM_UPPERCASE)
        );

        $this->assertSame(
            strtolower($this->testData['simple']['alphanumeric']),
            Converter::toAlphanumeric($this->testData['simple']['numeric'], 0, null, Converter::TRANSFORM_LOWERCASE)
        );
    }

    public function testToAlphanumericWithUnknownTransform()
    {
        $this->expectException(ConverterException::class);
        $this->expectExceptionMessage(ConverterException::E_TRANSFORM_TYPE);
        Converter::toAlphanumeric($this->testData['simple']['numeric'], 0, null, 777);
    }
}
