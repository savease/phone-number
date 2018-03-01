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
     * Test a valid phone number.
     */
    public function testValidPhoneNumber()
    {
        $phoneNumber = PhoneNumber::parse('048055555');

        self::assertSame('048055555', $phoneNumber->__toString());
        self::assertSame('4648055555', $phoneNumber->toMSISDN());
    }

    /**
     * Test empty phone number.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Phone number can not be empty
     */
    public function testEmptyPhoneNumber()
    {
        PhoneNumber::parse('');
    }

    /**
     * Test invalid phone number.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Phone number "FooBar" is invalid
     */
    public function testInvalidPhoneNumber()
    {
        PhoneNumber::parse('FooBar');
    }

    /**
     * Test tryParse method.
     */
    public function testTryParse()
    {
        self::assertNull(PhoneNumber::tryParse(''));
        self::assertNull(PhoneNumber::tryParse('foobar'));
        self::assertSame('4648055555', PhoneNumber::tryParse('048055555')->toMSISDN());
    }

    /**
     * Test isValid method.
     */
    public function testIsValid()
    {
        self::assertFalse(PhoneNumber::isValid(''));
        self::assertFalse(PhoneNumber::isValid('foobar'));
        self::assertTrue(PhoneNumber::isValid('048055555'));
    }
}
