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
     * @param string $phoneNumber            The phone number.
     * @param string $expectedStringValue    The expected string value.
     * @param string $expectedNationalFormat The expected national format value.
     * @param string $expectedMSISDN         The expected MSISDN value.
     * @param int    $expectedCountryCode    The expected country code.
     * @param string $expectedAreaCode       The expected area code.
     * @param string $expectedLocalNumber    The expected local number.
     */
    public function testParseValidPhoneNumber($phoneNumber, $expectedStringValue, $expectedNationalFormat, $expectedMSISDN, $expectedCountryCode, $expectedAreaCode, $expectedLocalNumber)
    {
        $phoneNumber1 = PhoneNumber::parse($phoneNumber);
        $phoneNumber2 = PhoneNumber::tryParse($phoneNumber);

        self::assertSame($expectedStringValue, $phoneNumber1->__toString());
        self::assertSame($expectedNationalFormat, $phoneNumber1->toNationalFormat());
        self::assertSame($expectedMSISDN, $phoneNumber1->toMSISDN());
        self::assertSame($expectedCountryCode, $phoneNumber1->getCountryCode());
        self::assertSame($expectedAreaCode, $phoneNumber1->getAreaCode());
        self::assertSame($expectedLocalNumber, $phoneNumber1->getLocalNumber());

        self::assertSame($expectedStringValue, $phoneNumber2->__toString());
        self::assertSame($expectedNationalFormat, $phoneNumber2->toNationalFormat());
        self::assertSame($expectedMSISDN, $phoneNumber2->toMSISDN());
        self::assertSame($expectedCountryCode, $phoneNumber2->getCountryCode());
        self::assertSame($expectedAreaCode, $phoneNumber2->getAreaCode());
        self::assertSame($expectedLocalNumber, $phoneNumber2->getLocalNumber());

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
            ['0480 55555', '+46 480 555 55', '0480-555 55', '4648055555', 46, '0480', '55555'],
            ['0480 42 40 00', '+46 480 42 40 00', '0480-42 40 00', '46480424000', 46, '0480', '424000'],
            ['0480 424000', '+46 480 42 40 00', '0480-42 40 00', '46480424000', 46, '0480', '424000'],
            ['0480424000', '+46 480 42 40 00', '0480-42 40 00', '46480424000', 46, '0480', '424000'],
            ['0046480424000', '+46 480 42 40 00', '0480-42 40 00', '46480424000', 46, '0480', '424000'],
            ['+46480424000', '+46 480 42 40 00', '0480-42 40 00', '46480424000', 46, '0480', '424000'],
            ['+46480424000', '+46 480 42 40 00', '0480-42 40 00', '46480424000', 46, '0480', '424000'],
            ['+46 8-1234567', '+46 8 123 45 67', '08-123 45 67', '4681234567', 46, '08', '1234567'],
            ['08 1234567', '+46 8 123 45 67', '08-123 45 67', '4681234567', 46, '08', '1234567'],
            ['08 12345678', '+46 8 123 456 78', '08-123 456 78', '46812345678', 46, '08', '12345678'],
            ['031-1234567', '+46 31 123 45 67', '031-123 45 67', '46311234567', 46, '031', '1234567'],
            ['0701234567', '+46 70 123 45 67', '070-123 45 67', '46701234567', 46, '070', '1234567'],
            ['00460701234567', '+46 70 123 45 67', '070-123 45 67', '46701234567', 46, '070', '1234567'],

            // Yet unhandled country codes.
            ['+47 01 23 45 67', '+4 701234567', '701234567', '4701234567', 4, '', '701234567'],
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
            ['FooBar', 'Phone number "FooBar" is invalid: Phone number contains invalid character "F".'],
            ['+46123456', 'Phone number "+46123456" is invalid: Phone number is too short.'],
            ['+12%34567', 'Phone number "+12%34567" is invalid: Phone number contains invalid character "%".'],
            ['+46 08-123 456 789', 'Phone number "+46 08-123 456 789" is invalid: Local part or phone number "123456789" must be between 5 and 8 digits.'],
            ['+46 0480-12 34', 'Phone number "+46 0480-12 34" is invalid: Local part or phone number "1234" must be between 5 and 8 digits.'],
        ];
    }
}
