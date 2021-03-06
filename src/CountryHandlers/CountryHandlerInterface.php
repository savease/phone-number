<?php

declare(strict_types=1);

namespace Savea\PhoneNumber\CountryHandlers;

/**
 * Country handler interface.
 */
interface CountryHandlerInterface
{
    /**
     * Parses a country-specific phone number.
     *
     * @param string      $phoneNumber The phone number.
     * @param string|null $areaCode    The parsed area code.
     * @param string|null $localNumber The parsed local number.
     * @param string|null $error       The error if parse failed.
     *
     * @return bool True if parse was successful, false otherwise.
     */
    public function parse(string $phoneNumber, ?string &$areaCode, ?string &$localNumber, ?string &$error): bool;

    /**
     * Formats a phone number to international format.
     *
     * @param string $areaCode    The area code.
     * @param string $localNumber The local number.
     *
     * @return string The formatted number.
     */
    public function formatInternational(string $areaCode, string $localNumber): string;

    /**
     * Formats a phone number to MSISDN format.
     *
     * @param string $areaCode    The area code.
     * @param string $localNumber The local number.
     *
     * @return string The formatted number.
     */
    public function formatMSISDN(string $areaCode, string $localNumber): string;

    /**
     * Formats a phone number to national format.
     *
     * @param string $areaCode    The area code.
     * @param string $localNumber The local number.
     *
     * @return string The formatted number.
     */
    public function formatNational(string $areaCode, string $localNumber): string;

    /**
     * Returns the ISO 3166 country code, two letters
     *
     * @return string|null  ISO 3166 country code, two letters
     */
    public function getISOCountryCode(): ?string;
}
