<?php

namespace Savea\PhoneNumber\CountryHandlers;

use Savea\PhoneNumber\PhoneNumberInterface;

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
     * @param PhoneNumberInterface $phoneNumber The phone number.
     *
     * @return string The formatted number.
     */
    public function formatInternational(PhoneNumberInterface $phoneNumber)
    {
        return $phoneNumber->getAreaCode() . $phoneNumber->getLocalNumber();
    }

    /**
     * Formats a phone number to MSISDN format.
     *
     * @param PhoneNumberInterface $phoneNumber The phone number.
     *
     * @return string The formatted number.
     */
    public function formatMSISDN(PhoneNumberInterface $phoneNumber)
    {
        return $phoneNumber->getAreaCode() . $phoneNumber->getLocalNumber();
    }
}
