<?php

namespace Savea\PhoneNumber\CountryHandlers;

/**
 * Handler for NO country codes.
 */
class NoCountryHandler implements CountryHandlerInterface
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
        if (preg_match("/[^0-9-]/", $phoneNumber, $matches)) {
            $error = 'Phone number contains invalid character "' . $matches[0] . '".';

            return false;
        }

        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // todo: handle numbers beginning with 0 or 1.
        // https://no.wikipedia.org/wiki/Nummerplan_(E.164)

        if (strlen($phoneNumber) !== 8) {
            $error = 'Local part of number "' . $phoneNumber . '" must be 8 digits.';

            return false;
        }

        $areaCode = '';
        $localNumber = $phoneNumber;

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
        return self::formatLocalNumber($localNumber);
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
        return $localNumber;
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
        return self::formatLocalNumber($localNumber);
    }

    /**
     * Returns the ISO 3166 country code, two letters
     *
     * @return string|null  ISO 3166 country code, two letters
     */
    public function getISOCountryCode()
    {
        return 'no';
    }

    /**
     * Formats a local number.
     *
     * @param string $localNumber The local number.
     *
     * @return string The formatted local number.
     */
    private static function formatLocalNumber($localNumber)
    {
        switch ($localNumber[0]) {
            case '2':
            case '3':
            case '5':
            case '6':
            case '7':
                $localNumber = substr($localNumber, 0, 2) . ' ' . substr($localNumber, 2, 2) . ' ' . substr($localNumber, 4, 2) . ' ' . substr($localNumber, 6, 2);
                break;
            case '4':
            case '8':
            case '9':
                $localNumber = substr($localNumber, 0, 3) . ' ' . substr($localNumber, 3, 2) . ' ' . substr($localNumber, 5, 3);
                break;
        }

        return $localNumber;
    }
}
