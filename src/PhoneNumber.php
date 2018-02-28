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
     * Parses the phone number.
     *
     * @param string $phoneNumber The phone number.
     *
     * @return PhoneNumber The parsed phone number.
     */
    public static function parse($phoneNumber)
    {
        if ($phoneNumber === '') {
            throw new \InvalidArgumentException('Phone number can not be empty');
        }

        // fixme: better rules and unit tests
        if (!preg_match("/^[0-9 ()+-]+$/", $phoneNumber)) {
            throw new \InvalidArgumentException('Phone number "' . $phoneNumber . '" is invalid');
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
     * @var string My phone number.
     */
    private $phoneNumber;
}
