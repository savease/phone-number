<?php

namespace Savea\PhoneNumber\CountryHandlers;

/**
 * Handler for CZ country codes.
 */
class CzCountryHandler extends DefaultCountryHandler implements CountryHandlerInterface
{
    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * Returns the ISO 3166 country code, two letters
     *
     * @return string|null  ISO 3166 country code, two letters
     */
    public function getISOCountryCode()
    {
        return 'cz';
    }
}
