<?php

namespace Savea\PhoneNumber;

/**
 * Class PhoneNumber.
 */
class PhoneNumber implements PhoneNumberInterface
{
    /**
     * Returns the country code.
     *
     * @return int The country code.
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Returns the phone number as an MSISDN.
     *
     * @return string The phone number as an MSISDN.
     */
    public function toMSISDN()
    {
        return $this->countryCode . $this->localNumber;
    }

    /**
     * Returns the phone number as a string.
     *
     * @return string The phone number as a string.
     */
    public function __toString()
    {
        return '+' . $this->countryCode . ' ' . $this->localNumber;
    }

    /**
     * Returns true if a phone number is valid, false otherwise.
     *
     * @param string $phoneNumber The phone number.
     *
     * @return bool True if phone number is valid, false otherwise.
     */
    public static function isValid($phoneNumber)
    {
        return self::doParse($phoneNumber);
    }

    /**
     * Parses the phone number.
     *
     * @param string $phoneNumber The phone number.
     *
     * @return PhoneNumber The parsed phone number.
     *
     * @throws \InvalidArgumentException If parsing failed.
     */
    public static function parse($phoneNumber)
    {
        if (!self::doParse($phoneNumber, $countryCode, $localNumber, $error)) {
            throw new \InvalidArgumentException($error);
        }

        return new self($countryCode, $localNumber);
    }

    /**
     * Tries to parse a phone number and return null if parsing failed.
     *
     * @param string $phoneNumber The phone number.
     *
     * @return PhoneNumber|null The phone number or null if parsing failed.
     */
    public static function tryParse($phoneNumber)
    {
        if (!self::doParse($phoneNumber, $countryCode, $localNumber)) {
            return null;
        }

        return new self($countryCode, $localNumber);
    }

    /**
     * PhoneNumber constructor.
     *
     * @param int    $countryCode The country code.
     * @param string $localNumber The local number.
     */
    private function __construct($countryCode, $localNumber)
    {
        $this->countryCode = $countryCode;
        $this->localNumber = $localNumber;
    }

    /**
     * Tries to parse a phone number and returns true if successful, false otherwise.
     *
     * @param string      $phoneNumber The phone number to parse.
     * @param int|null    $countryCode The parsed country code.
     * @param string|null $localNumber The parsed local number.
     * @param string|null $error       The error if parse failed.
     *
     * @return bool True if successful or false.
     */
    private static function doParse($phoneNumber, &$countryCode = null, &$localNumber = null, &$error = null)
    {
        $originalPhoneNumber = $phoneNumber;
        $phoneNumber = preg_replace('/\s+/', '', $phoneNumber);

        if ($phoneNumber === '') {
            $error = 'Phone number can not be empty.';

            return false;
        }

        if (!self::doParseCountryCode($phoneNumber, $countryCode, $error)) {
            $error = 'Phone number "' . $originalPhoneNumber . '" is invalid: ' . $error;

            return false;
        }

        if ($countryCode === 46) {
            $phoneNumber = ltrim($phoneNumber, '0');
        }

        if (!preg_match("/^[0-9()+-]+$/", $phoneNumber)) {
            $error = 'Phone number "' . $originalPhoneNumber . '" is invalid.';

            return false;
        }

        $localNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        return true;
    }

    /**
     * Parses the country code.
     *
     * @param string      $phoneNumber The phone number. This will be modified to contain the phone number excluding country code.
     * @param int|null    $countryCode The parsed country code.
     * @param string|null $error       The error if parse failed.
     *
     * @return bool True if successful, false otherwise.
     */
    private static function doParseCountryCode(&$phoneNumber, &$countryCode = null, &$error = null)
    {
        $hasCountryCode = false;

        if (substr($phoneNumber, 0, 1) === '+') {
            $phoneNumber = substr($phoneNumber, 1);
            $hasCountryCode = true;
        } elseif (substr($phoneNumber, 0, 2) === '00') {
            $phoneNumber = substr($phoneNumber, 2);
            $hasCountryCode = true;
        }

        if (!$hasCountryCode) {
            $countryCode = 46;

            return true;
        }

        $validCountryCodes = ['46']; // fixme: more

        foreach ($validCountryCodes as $validCountryCode) {
            $countryCodeLength = strlen($validCountryCode);

            if (substr($phoneNumber, 0, $countryCodeLength) === $validCountryCode) {
                $countryCode = intval($validCountryCode);
                $phoneNumber = substr($phoneNumber, $countryCodeLength);

                return intval($validCountryCode);
            }
        }

        // There was a country code that is not yet handled.
        // In the future this might generate an error, but for now just use the first 1 digit.
        $countryCode = intval(substr($phoneNumber, 0, 1));
        if (!ctype_digit($countryCode)) {
            $error = 'Country code must begin with a digit.';
        }

        $phoneNumber = substr($phoneNumber, 1);

        return $countryCode;
    }

    /**
     * @var int The country code.
     */
    private $countryCode;

    /**
     * @var string The local number.
     */
    private $localNumber;
}
