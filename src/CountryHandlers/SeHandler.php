<?php

namespace Savea\PhoneNumber\CountryHandlers;

/**
 * Handler for SE country codes.
 */
class SeHandler
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

        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (strlen($phoneNumber) < 7) {
            $error = 'Phone number is too short.';

            return false;
        }

        $areaCodeLength = self::getAreaCodeLength($phoneNumber);
        $areaCode = substr($phoneNumber, 0, $areaCodeLength);
        $localNumber = substr($phoneNumber, $areaCodeLength);

        return true;
    }

    /**
     * Returns the length of the area code part for a phone number.
     *
     * @param string $phoneNumber The phone number.
     *
     * @return int The length of the area code.
     */
    private static function getAreaCodeLength($phoneNumber)
    {
        if (substr($phoneNumber, 0, 1) === '8') {
            return 1;
        }

        if (substr($phoneNumber, 0, 1) === '7') {
            return 2;
        }

        if (in_array(substr($phoneNumber, 0, 2), ['11', '13', '16', '18', '19', '21', '23', '26', '31', '33', '35', '36', '40', '42', '44', '46', '54', '60', '63', '90'])) {
            return 2;
        }

        return 3;
    }
}
