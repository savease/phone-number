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
        $phoneNumber = trim($phoneNumber);

        if ($phoneNumber === '') {
            $error = 'Phone number can not be empty';

            return false;
        }

        if (!preg_match("/^[0-9 ()+-]+$/", $phoneNumber)) {
            $error = 'Phone number "' . $phoneNumber . '" is invalid';

            return false;
        }

        if (substr($phoneNumber, 0, 2) === '00') {
            // Number starting with '00'...
            $countryCode = intval(substr($phoneNumber, 2, 2)); // fixme: country code is not always 2 characters.
            $localNumber = substr($phoneNumber, 4);
        } elseif (substr($phoneNumber, 0, 1) === '+') {
            // Number starting with '+'...
            $countryCode = intval(substr($phoneNumber, 1, 2)); // fixme: country code is not always 2 characters.
            $localNumber = substr($phoneNumber, 3);
        } else {
            $countryCode = 46;
            $localNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            $localNumber = ltrim($localNumber, '0');
        }

        return true;
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
