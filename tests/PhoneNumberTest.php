<?php

namespace Savea\PhoneNumber\Tests;

use PHPUnit\Framework\TestCase;
use Savea\PhoneNumber\PhoneNumber;

/**
 * Class PhoneNumberTest.
 */
class PhoneNumberTest extends TestCase
{
    /**
     * Test parse valid phone number.
     *
     * @dataProvider parseValidPhoneNumberProvider
     *
     * @param string $phoneNumber         The phone number.
     * @param string $expectedStringValue The expected string value.
     * @param string $expectedMSISDN      The expected MSISDN value.
     */
    public function testParseValidPhoneNumber($phoneNumber, $expectedStringValue, $expectedMSISDN)
    {
        $phoneNumber1 = PhoneNumber::parse($phoneNumber);
        $phoneNumber2 = PhoneNumber::tryParse($phoneNumber);

        self::assertSame($expectedStringValue, $phoneNumber1->__toString());
        self::assertSame($expectedMSISDN, $phoneNumber1->toMSISDN());

        self::assertSame($expectedStringValue, $phoneNumber2->__toString());
        self::assertSame($expectedMSISDN, $phoneNumber2->toMSISDN());

        self::assertTrue(PhoneNumber::isValid($phoneNumber));
    }

    /**
     * Data provider for parse valid phone number tests.
     *
     * @return array The data.
     */
    public function parseValidPhoneNumberProvider()
    {
        return [
            // fixme: String value should be '+46 480 42 40 00' for all these test cases
            ['0480 42 40 00', '0480 42 40 00', '46480424000'],
            ['0480 424000', '0480 424000', '46480424000'],
            ['0480424000', '0480424000', '46480424000'],
            ['+46480424000', '+46480424000', '46480424000'],
        ];
    }

    /**
     * Test parse invalid phone number.
     *
     * @dataProvider invalidPhoneNumberDataProvider
     *
     * @param string $phoneNumber   The phone number.
     * @param string $expectedError The expected error.
     */
    public function testParseInvalidPhoneNumber($phoneNumber, $expectedError)
    {
        $error = null;
        try {
            PhoneNumber::parse($phoneNumber);
        } catch (\InvalidArgumentException $exception) {
            $error = $exception->getMessage();
        }

        self::assertSame($expectedError, $error);
        self::assertNull(PhoneNumber::tryParse($phoneNumber));
        self::assertFalse(PhoneNumber::isValid($phoneNumber));
    }

    /**
     * Data provider for invalid phone number tests.
     *
     * @return array The data.
     */
    public function invalidPhoneNumberDataProvider()
    {
        return [
            ['', 'Phone number can not be empty'],
            ['FooBar', 'Phone number "FooBar" is invalid'],
        ];
    }
}
