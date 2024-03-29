<?php

declare(strict_types=1);

namespace Savea\PhoneNumber;

/**
 * Interface PhoneNumberInterface.
 */
interface PhoneNumberInterface
{
    /**
     * Returns the area code.
     *
     * @return string The area code.
     */
    public function getAreaCode(): string;

    /**
     * Returns the country code.
     *
     * @return int The country code.
     */
    public function getCountryCode(): int;

    /**
     * Returns the ISO 3166 country code, two letters
     *
     * @return string|null  ISO 3166 country code, two letters
     */
    public function getISOCountryCode(): ?string;

    /**
     * Returns the local number.
     *
     * @return string The local number.
     */
    public function getLocalNumber(): string;

    /**
     * Returns the phone number in compact format, e.g. +46701740605
     *
     * @return string The phone number in compact format.
     */
    public function toCompactFormat(): string;

    /**
     * Returns the phone number in international format, e.g. +46 70 174 06 05
     *
     * @return string The phone number in international format.
     */
    public function toInternationalFormat(): string;

    /**
     * Returns the phone number as an MSISDN.
     *
     * @return string The phone number as an MSISDN.
     */
    public function toMSISDN(): string;

    /**
     * Returns the phone number in national format, e.g. 070-174 06 05
     *
     * @return string The phone number in national format.
     */
    public function toNationalFormat(): string;

    /**
     * Returns the phone number as a string.
     *
     * @return string The phone number as a string.
     */
    public function __toString(): string;
}
