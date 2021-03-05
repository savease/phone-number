<?php

declare(strict_types=1);

namespace Savea\PhoneNumber;

use Savea\PhoneNumber\CountryHandlers\CountryHandlerInterface;
use Savea\PhoneNumber\CountryHandlers\DefaultCountryHandler;
use Savea\PhoneNumber\CountryHandlers\AtCountryHandler;
use Savea\PhoneNumber\CountryHandlers\AuCountryHandler;
use Savea\PhoneNumber\CountryHandlers\ChCountryHandler;
use Savea\PhoneNumber\CountryHandlers\CzCountryHandler;
use Savea\PhoneNumber\CountryHandlers\DkCountryHandler;
use Savea\PhoneNumber\CountryHandlers\DeCountryHandler;
use Savea\PhoneNumber\CountryHandlers\EeCountryHandler;
use Savea\PhoneNumber\CountryHandlers\FiCountryHandler;
use Savea\PhoneNumber\CountryHandlers\FrCountryHandler;
use Savea\PhoneNumber\CountryHandlers\GbCountryHandler;
use Savea\PhoneNumber\CountryHandlers\LvCountryHandler;
use Savea\PhoneNumber\CountryHandlers\NlCountryHandler;
use Savea\PhoneNumber\CountryHandlers\NoCountryHandler;
use Savea\PhoneNumber\CountryHandlers\PlCountryHandler;
use Savea\PhoneNumber\CountryHandlers\SeCountryHandler;

/**
 * Class PhoneNumber.
 */
class PhoneNumber implements PhoneNumberInterface
{
    /**
     * Returns the area code.
     *
     * @return string The area code.
     */
    public function getAreaCode()
    {
        return $this->areaCode;
    }

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
     * Returns the local number.
     *
     * @return string The local number.
     */
    public function getLocalNumber()
    {
        return $this->localNumber;
    }

    /**
     * Returns the phone number as an MSISDN.
     *
     * @return string The phone number as an MSISDN.
     */
    public function toMSISDN()
    {
        return $this->countryCode . $this->countryHandler->formatMSISDN($this->areaCode, $this->localNumber);
    }

    /**
     * Returns the phone number in national format.
     *
     * @return string The phone number in national format.
     */
    public function toNationalFormat()
    {
        return $this->countryHandler->formatNational($this->areaCode, $this->localNumber);
    }

    /**
     * Returns the phone number as a string.
     *
     * @return string The phone number as a string.
     */
    public function __toString()
    {
        return '+' . $this->countryCode . ' ' . $this->countryHandler->formatInternational($this->areaCode, $this->localNumber);
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
        if (!self::doParse($phoneNumber, $countryHandler, $countryCode, $areaCode, $localNumber, $ISOCountryCode, $error)) {
            throw new \InvalidArgumentException($error);
        }

        return new self($countryHandler, $countryCode, $areaCode, $localNumber, $ISOCountryCode);
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
        if (!self::doParse($phoneNumber, $countryHandler, $countryCode, $areaCode, $localNumber, $ISOCountryCode)) {
            return null;
        }

        return new self($countryHandler, $countryCode, $areaCode, $localNumber, $ISOCountryCode);
    }

    /**
     * Returns the ISO 3166 country code, two letters
     *
     * @return string|null  ISO 3166 country code, two letters
     */
    public function getISOCountryCode()
    {
        return $this->ISOCountryCode;
    }

    /**
     * PhoneNumber constructor.
     *
     * @param CountryHandlerInterface $countryHandler  The country handler.
     * @param int                     $countryCode     The country code.
     * @param string                  $areaCode        The area code.
     * @param string                  $localNumber     The local number.
     * @param string                  $ISOCountryCode  ISO 3166 country code, two letters.
     */
    private function __construct(CountryHandlerInterface $countryHandler, $countryCode, $areaCode, $localNumber, $ISOCountryCode)
    {
        $this->countryHandler = $countryHandler;
        $this->countryCode = $countryCode;
        $this->areaCode = $areaCode;
        $this->localNumber = $localNumber;
        $this->ISOCountryCode = $ISOCountryCode;
    }

    /**
     * Tries to parse a phone number and returns true if successful, false otherwise.
     *
     * @param string                       $phoneNumber     The phone number to parse.
     * @param CountryHandlerInterface|null $countryHandler  The parsed country handler.
     * @param int|null                     $countryCode     The parsed country code.
     * @param string|null                  $areaCode        The parsed area code.
     * @param string|null                  $localNumber     The parsed local number.
     * @param string|null                  $error           The error if parse failed.
     * @param string|null                  $ISOCountryCode  ISO 3166 country code, two letters.
     *
     * @return bool True if successful or false.
     */
    private static function doParse($phoneNumber, CountryHandlerInterface &$countryHandler = null, &$countryCode = null, &$areaCode = null, &$localNumber = null, &$ISOCountryCode = null, &$error = null)
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

        $countryHandler = self::getCountryHandler($countryCode);
        if (!$countryHandler->parse($phoneNumber, $areaCode, $localNumber, $error)) {
            $error = 'Phone number "' . $originalPhoneNumber . '" is invalid: ' . $error;

            return false;
        }

        $ISOCountryCode = $countryHandler->getISOCountryCode();

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
            $phoneNumber = ltrim($phoneNumber, '0');

            return true;
        }

        foreach (array_keys(self::$countryHandlers) as $countryCode) {
            $countryCode = strval($countryCode);
            $countryCodeLength = strlen($countryCode);

            if (substr($phoneNumber, 0, $countryCodeLength) === $countryCode) {
                $countryCode = intval($countryCode);
                $phoneNumber = substr($phoneNumber, $countryCodeLength);

                return true;
            }
        }

        // There was a country code that is not yet handled.
        // In the future this might generate an error, but for now just use the first 1 digit.
        $countryCodeCharacter = substr($phoneNumber, 0, 1);
        if (!ctype_digit($countryCodeCharacter)) {
            $error = 'Country code must begin with a digit.';

            return false;
        }

        $countryCode = intval($countryCodeCharacter);
        $phoneNumber = substr($phoneNumber, 1);

        return true;
    }

    /**
     * Returns the country handler for a country code.
     *
     * @param int $countryCode The country handler.
     *
     * @return CountryHandlerInterface The country handler.
     */
    private static function getCountryHandler($countryCode)
    {
        if (isset(self::$countryHandlers[$countryCode])) {
            $handlerClass = self::$countryHandlers[$countryCode];

            return new $handlerClass();
        }

        return new DefaultCountryHandler();
    }

    /**
     * @var CountryHandlerInterface The country handler.
     */
    private $countryHandler;

    /**
     * @var int The country code.
     */
    private $countryCode;

    /**
     * @var string|null The ISO 3166 country code, two letters.
     */
    private $ISOCountryCode;

    /**
     * @var string The area code.
     */
    private $areaCode;

    /**
     * @var string The local number.
     */
    private $localNumber;

    /**
     * @var array The country handlers.
     */
    private static $countryHandlers = [
        31  => NlCountryHandler::class,
        33  => FrCountryHandler::class,
        358 => FiCountryHandler::class,
        371 => LvCountryHandler::class,
        372 => EeCountryHandler::class,
        39  => AuCountryHandler::class,
        41  => ChCountryHandler::class,
        420 => CzCountryHandler::class,
        43  => AtCountryHandler::class,
        44  => GbCountryHandler::class,
        45  => DkCountryHandler::class,
        46  => SeCountryHandler::class,
        47  => NoCountryHandler::class,
        48  => PlCountryHandler::class,
        49  => DeCountryHandler::class,
    ];
}
