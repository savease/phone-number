<?php

declare(strict_types=1);

namespace Savea\PhoneNumber;

use Savea\PhoneNumber\CountryHandlers\AtCountryHandler;
use Savea\PhoneNumber\CountryHandlers\AuCountryHandler;
use Savea\PhoneNumber\CountryHandlers\ChCountryHandler;
use Savea\PhoneNumber\CountryHandlers\CountryHandlerInterface;
use Savea\PhoneNumber\CountryHandlers\CzCountryHandler;
use Savea\PhoneNumber\CountryHandlers\DeCountryHandler;
use Savea\PhoneNumber\CountryHandlers\DefaultCountryHandler;
use Savea\PhoneNumber\CountryHandlers\DkCountryHandler;
use Savea\PhoneNumber\CountryHandlers\EeCountryHandler;
use Savea\PhoneNumber\CountryHandlers\FiCountryHandler;
use Savea\PhoneNumber\CountryHandlers\FrCountryHandler;
use Savea\PhoneNumber\CountryHandlers\GbCountryHandler;
use Savea\PhoneNumber\CountryHandlers\InCountryHandler;
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
    public function getAreaCode(): string
    {
        return $this->areaCode;
    }

    /**
     * Returns the country code.
     *
     * @return int The country code.
     */
    public function getCountryCode(): int
    {
        return $this->countryCode;
    }

    /**
     * Returns the ISO 3166 country code, two letters
     *
     * @return string|null  ISO 3166 country code, two letters
     */
    public function getISOCountryCode(): ?string
    {
        return $this->ISOCountryCode;
    }

    /**
     * Returns the local number.
     *
     * @return string The local number.
     */
    public function getLocalNumber(): string
    {
        return $this->localNumber;
    }

    /**
     * Returns the phone number in compact format, e.g. +46701740605
     *
     * @return string The phone number in compact format.
     */
    public function toCompactFormat(): string
    {
        return '+' . $this->toMSISDN();
    }

    /**
     * Returns the phone number in international format, e.g. +46 70 174 06 05
     *
     * @return string The phone number in international format.
     */
    public function toInternationalFormat(): string
    {
        return '+' . $this->countryCode . ' ' . $this->countryHandler->formatInternational($this->areaCode, $this->localNumber);
    }

    /**
     * Returns the phone number as an MSISDN.
     *
     * @return string The phone number as an MSISDN.
     */
    public function toMSISDN(): string
    {
        return $this->countryCode . $this->countryHandler->formatMSISDN($this->areaCode, $this->localNumber);
    }

    /**
     * Returns the phone number in national format, e.g. 070-174 06 05
     *
     * @return string The phone number in national format.
     */
    public function toNationalFormat(): string
    {
        return $this->countryHandler->formatNational($this->areaCode, $this->localNumber);
    }

    /**
     * Returns the phone number as a string.
     *
     * @return string The phone number as a string.
     */
    public function __toString(): string
    {
        return $this->toInternationalFormat();
    }

    /**
     * Returns true if a phone number is valid, false otherwise.
     *
     * @param string $phoneNumber The phone number.
     *
     * @return bool True if phone number is valid, false otherwise.
     */
    public static function isValid(string $phoneNumber): bool
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
    public static function parse(string $phoneNumber): PhoneNumber
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
    public static function tryParse(string $phoneNumber): ?PhoneNumber
    {
        if (!self::doParse($phoneNumber, $countryHandler, $countryCode, $areaCode, $localNumber, $ISOCountryCode)) {
            return null;
        }

        return new self($countryHandler, $countryCode, $areaCode, $localNumber, $ISOCountryCode);
    }

    /**
     * PhoneNumber constructor.
     *
     * @param CountryHandlerInterface $countryHandler The country handler.
     * @param int                     $countryCode    The country code.
     * @param string                  $areaCode       The area code.
     * @param string                  $localNumber    The local number.
     * @param string|null             $ISOCountryCode ISO 3166 country code, two letters.
     */
    private function __construct(CountryHandlerInterface $countryHandler, int $countryCode, string $areaCode, string $localNumber, ?string $ISOCountryCode)
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
     * @param string                       $phoneNumber    The phone number to parse.
     * @param CountryHandlerInterface|null $countryHandler The parsed country handler.
     * @param int|null                     $countryCode    The parsed country code.
     * @param string|null                  $areaCode       The parsed area code.
     * @param string|null                  $localNumber    The parsed local number.
     * @param string|null                  $error          The error if parse failed.
     * @param string|null                  $ISOCountryCode ISO 3166 country code, two letters.
     *
     * @return bool True if successful or false.
     */
    private static function doParse(string $phoneNumber, CountryHandlerInterface &$countryHandler = null, ?int &$countryCode = null, ?string &$areaCode = null, ?string &$localNumber = null, ?string &$ISOCountryCode = null, ?string &$error = null): bool
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
    private static function doParseCountryCode(string &$phoneNumber, ?int &$countryCode = null, ?string &$error = null): bool
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

        foreach (array_keys(self::COUNTRY_HANDLERS) as $countryCode) {
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
    private static function getCountryHandler(int $countryCode)
    {
        if (isset(self::COUNTRY_HANDLERS[$countryCode])) {
            $handlerClass = self::COUNTRY_HANDLERS[$countryCode];

            return new $handlerClass();
        }

        return new DefaultCountryHandler();
    }

    /**
     * @var CountryHandlerInterface The country handler.
     */
    private CountryHandlerInterface $countryHandler;

    /**
     * @var int The country code.
     */
    private int $countryCode;

    /**
     * @var string|null The ISO 3166 country code, two letters.
     */
    private ?string $ISOCountryCode;

    /**
     * @var string The area code.
     */
    private string $areaCode;

    /**
     * @var string The local number.
     */
    private string $localNumber;

    /**
     * @var array The country handlers.
     */
    private const COUNTRY_HANDLERS = [
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
        91  => InCountryHandler::class,
    ];
}
