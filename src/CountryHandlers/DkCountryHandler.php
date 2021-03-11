<?php

declare(strict_types=1);

namespace Savea\PhoneNumber\CountryHandlers;

/**
 * Handler for DK country codes.
 */
class DkCountryHandler extends DefaultCountryHandler implements CountryHandlerInterface
{
    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * Returns the ISO 3166 country code, two letters
     *
     * @return string|null  ISO 3166 country code, two letters
     */
    public function getISOCountryCode(): ?string
    {
        return 'dk';
    }
}
