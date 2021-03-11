<?php

declare(strict_types=1);

namespace Savea\PhoneNumber\CountryHandlers;

/**
 * Handler for NL country codes.
 */
class NlCountryHandler extends DefaultCountryHandler implements CountryHandlerInterface
{
    /** @noinspection PhpMissingParentCallCommonInspection */
    /**
     * Returns the ISO 3166 country code, two letters
     *
     * @return string|null  ISO 3166 country code, two letters
     */
    public function getISOCountryCode(): ?string
    {
        return 'nl';
    }
}
