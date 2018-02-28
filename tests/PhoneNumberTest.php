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
}
