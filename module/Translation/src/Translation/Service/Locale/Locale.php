<?php

namespace Translation\Service\Locale;

use Translation\Exception\LocaleNotFound;

/**
 * TranslatorHandler
 * 
 *
 * @package translation
 * @subpackage locale
 */
class Locale 
{

    /**
     * Locale Constants
     */
    const LOCALE_EN_US = "en_US";
    const LOCALE_AR_AR = "ar_AR";

    /**
     * Cookie Key Constant
     */
    const COOKIE_KEY = "locale";

    protected $currentLocale;
    protected $defaultLocale;

    public function __construct()
    {
        // by default the application is in English
        $this->defaultLocale = self::LOCALE_EN_US;
    }

    public function getCurrentLocale()
    {
        if (null !== $this->currentLocale) {
            return $this->currentLocale;
        }
        return $this->getDefaultLocale();
    }

    public function setCurrentLocale($locale)
    {
        if (!in_array($locale, $this->getAvailableLocales())) {
            throw new LocaleNotFound(sprintf(LocaleNotFound::MESSAGE, $locale));
        }
        $this->currentLocale = $locale;
    }

    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    public function getAvailableLocales()
    {
        return [
            self::LOCALE_AR_AR,
            self::LOCALE_EN_US
        ];
    }

    public function saveLocaleInCookies()
    {
        setcookie(self::COOKIE_KEY, $this->getCurrentLocale(), time() + 365 * 60 * 60 * 24, '/');
    }

    public function loadLocaleFromCookies()
    {
        if (isset($_COOKIE[self::COOKIE_KEY])) {
            $this->setCurrentLocale($_COOKIE[self::COOKIE_KEY]);
        }
        return $this->getCurrentLocale();
    }

}
