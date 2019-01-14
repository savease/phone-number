<?php

namespace Savea\PhoneNumber\CountryHandlers;

/**
 * Handler for FR country codes.
 */
class FrCountryHandler extends DefaultCountryHandler implements CountryHandlerInterface
{
    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * Returns the ISO 3166 country code, two letters
     *
     * @return string|null  ISO 3166 country code, two letters
     */
    public function getISOCountryCode()
    {
        return 'fr';
    }
}
