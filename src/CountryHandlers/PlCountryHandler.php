<?php

namespace Savea\PhoneNumber\CountryHandlers;

/**
 * Handler for PL country codes.
 */
class PlCountryHandler extends DefaultCountryHandler implements CountryHandlerInterface
{
    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * Returns the ISO 3166 country code, two letters
     *
     * @return string|null  ISO 3166 country code, two letters
     */
    public function getISOCountryCode()
    {
        return 'pl';
    }
}