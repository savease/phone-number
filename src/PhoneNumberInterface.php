<?php

namespace Savea\PhoneNumber;

/**
 * Interface PhoneNumberInterface.
 */
interface PhoneNumberInterface
{
    /**
     * Returns the area code.
     *
     * @return string The area code.
     */
    public function getAreaCode();

    /**
     * Returns the country code.
     *
     * @return int The country code.
     */
    public function getCountryCode();

    /**
     * Returns the local number.
     *
     * @return string The local number.
     */
    public function getLocalNumber();

    /**
     * Returns the phone number as an MSISDN.
     *
     * @return string The phone number as an MSISDN.
     */
    public function toMSISDN();

    /**
     * Returns the phone number as a string.
     *
     * @return string The phone number as a string.
     */
    public function __toString();
}
