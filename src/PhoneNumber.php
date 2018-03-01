<?php

namespace Savea\PhoneNumber;

/**
 * Class PhoneNumber.
 */
class PhoneNumber implements PhoneNumberInterface
{
    /**
     * Returns the phone number as a string.
     *
     * @return string The phone number as a string.
     */
    public function __toString()
    {
        return trim($this->phoneNumber);
    }

    /**
     * Returns the phone number as an MSISDN.
     *
     * @return string The phone number as an MSISDN.
     */
    public function toMSISDN()
    {
        $result = preg_replace('/[^0-9]/', '', $this->phoneNumber);

        if ($this->phoneNumber[0] === '0') {
            // Phone number does not include country code.
            $result = '46' . substr($result, 1);
        }

        return $result;
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
        if (!self::doParse($phoneNumber, $error)) {
            throw new \InvalidArgumentException($error);
        }

        return new self($phoneNumber);
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
        if (!self::doParse($phoneNumber)) {
            return null;
        }

        return new self($phoneNumber);
    }

    /**
     * PhoneNumber constructor.
     *
     * @param string $phoneNumber The phone number.
     */
    private function __construct($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Tries to parse a phone number and returns true if successful, false otherwise.
     *
     * @param string      $phoneNumber The phone number to parse.
     * @param string|null $error       The error if parse failed.
     *
     * @return bool True if successful or false.
     */
    private static function doParse($phoneNumber, &$error = null)
    {
        if ($phoneNumber === '') {
            $error = 'Phone number can not be empty';

            return false;
        }

        // fixme: better rules and unit tests
        if (!preg_match("/^[0-9 ()+-]+$/", $phoneNumber)) {
            $error = 'Phone number "' . $phoneNumber . '" is invalid';

            return false;
        }

        return true;
    }

    /**
     * @var string My phone number.
     */
    private $phoneNumber;
}
