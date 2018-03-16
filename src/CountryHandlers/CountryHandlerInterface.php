<?php

namespace Savea\PhoneNumber\CountryHandlers;

/**
 * Country handler interface.
 */
interface CountryHandlerInterface
{
    /**
     * Parses a country-specific phone number.
     *
     * @param string $phoneNumber The phone number.
     * @param string $areaCode    The parsed area code.
     * @param string $localNumber The parsed local number.
     * @param string $error       The error i parse failed.
     *
     * @return bool True if parse was successful, false otherwise.
     */
    public function parse($phoneNumber, &$areaCode, &$localNumber, &$error);

    /**
     * Formats a phone number to international format.
     *
     * @param string $areaCode    The area code.
     * @param string $localNumber The local number.
     *
     * @return string The formatted number.
     */
    public function formatInternational($areaCode, $localNumber);

    /**
     * Formats a phone number to MSISDN format.
     *
     * @param string $areaCode    The area code.
     * @param string $localNumber The local number.
     *
     * @return string The formatted number.
     */
    public function formatMSISDN($areaCode, $localNumber);

    /**
     * Formats a phone number to national format.
     *
     * @param string $areaCode    The area code.
     * @param string $localNumber The local number.
     *
     * @return string The formatted number.
     */
    public function formatNational($areaCode, $localNumber);
}
