<?php

namespace Savea\PhoneNumber\CountryHandlers;

use Savea\PhoneNumber\PhoneNumberInterface;

/**
 * Handler for SE country codes.
 */
class SeCountryHandler implements CountryHandlerInterface
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
        // Let's be nice and accept numbers with loading zeroes.
        $phoneNumber = ltrim($phoneNumber, '0');

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
        $areaCode = '0' . substr($phoneNumber, 0, $areaCodeLength);
        $localNumber = substr($phoneNumber, $areaCodeLength);

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
        return substr($phoneNumber->getAreaCode(), 1) . ' ' . self::formatLocalNumber($phoneNumber->getLocalNumber());
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
        return substr($phoneNumber->getAreaCode(), 1) . $phoneNumber->getLocalNumber();
    }

    /**
     * Formats a phone number to national format.
     *
     * @param PhoneNumberInterface $phoneNumber The phone number.
     *
     * @return string The formatted number.
     */
    public function formatNational(PhoneNumberInterface $phoneNumber)
    {
        return $phoneNumber->getAreaCode() . '-' . self::formatLocalNumber($phoneNumber->getLocalNumber());
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
        switch (strlen($localNumber)) {
            case 5:
                $localNumber = substr($localNumber, 0, 3) . ' ' . substr($localNumber, 3);
                break;
            case 6:
                $localNumber = substr($localNumber, 0, 2) . ' ' . substr($localNumber, 2, 2) . ' ' . substr($localNumber, 4, 2);
                break;
            case 7:
                $localNumber = substr($localNumber, 0, 3) . ' ' . substr($localNumber, 3, 2) . ' ' . substr($localNumber, 5, 2);
                break;
            case 8:
                $localNumber = substr($localNumber, 0, 3) . ' ' . substr($localNumber, 3, 3) . ' ' . substr($localNumber, 6, 2);
                break;
        }

        return $localNumber;
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
