<?php

namespace Savea\PhoneNumber\CountryHandlers;

/**
 * Default country handler class.
 */
class DefaultCountryHandler implements CountryHandlerInterface
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
    public function parse($phoneNumber, &$areaCode, &$localNumber, &$error)
    {
        if (preg_match("/[^0-9()+-]/", $phoneNumber, $matches)) {
            $error = 'Phone number contains invalid character "' . $matches[0] . '".';

            return false;
        }

        $areaCode = '';
        $localNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        return true;
    }

    /**
     * Formats a phone number to international format.
     *
     * @param string $areaCode    The area code.
     * @param string $localNumber The local number.
     *
     * @return string The formatted number.
     */
    public function formatInternational($areaCode, $localNumber)
    {
        return $areaCode . $localNumber;
    }

    /**
     * Formats a phone number to MSISDN format.
     *
     * @param string $areaCode    The area code.
     * @param string $localNumber The local number.
     *
     * @return string The formatted number.
     */
    public function formatMSISDN($areaCode, $localNumber)
    {
        return $areaCode . $localNumber;
    }

    /**
     * Formats a phone number to national format.
     *
     * @param string $areaCode    The area code.
     * @param string $localNumber The local number.
     *
     * @return string The formatted number.
     */
    public function formatNational($areaCode, $localNumber)
    {
        return $areaCode . $localNumber;
    }
}
