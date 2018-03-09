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
     * @param int    $expectedCountryCode The expected country code.
     */
    public function testParseValidPhoneNumber($phoneNumber, $expectedStringValue, $expectedMSISDN, $expectedCountryCode)
    {
        $phoneNumber1 = PhoneNumber::parse($phoneNumber);
        $phoneNumber2 = PhoneNumber::tryParse($phoneNumber);

        self::assertSame($expectedStringValue, $phoneNumber1->__toString());
        self::assertSame($expectedMSISDN, $phoneNumber1->toMSISDN());
        self::assertSame($expectedCountryCode, $phoneNumber1->getCountryCode());

        self::assertSame($expectedStringValue, $phoneNumber2->__toString());
        self::assertSame($expectedMSISDN, $phoneNumber2->toMSISDN());
        self::assertSame($expectedCountryCode, $phoneNumber2->getCountryCode());

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
            ['0480 42 40 00', '+46 480424000', '46480424000', 46],
            ['0480 424000', '+46 480424000', '46480424000', 46],
            ['0480424000', '+46 480424000', '46480424000', 46],
            ['0046480424000', '+46 480424000', '46480424000', 46],
            ['+46480424000', '+46 480424000', '46480424000', 46],

            ['+47 01 23 45 67', '+4 701234567', '4701234567', 4],
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
            ['', 'Phone number can not be empty.'],
            [' ', 'Phone number can not be empty.'],
            ['+x12345678', 'Phone number "+x12345678" is invalid: Country code must begin with a digit.'],
            ['FooBar', 'Phone number "FooBar" is invalid.'],
        ];
    }
}
