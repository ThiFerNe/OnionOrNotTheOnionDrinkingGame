<?php

namespace logics;

require_once(__DIR__ . "/further/LocalizationStore.php");

class LocalizationLogic
{
    public const PREFIX = "LocalizationLogic_";

    public const SUFFIX_CURRENT_LOCALE = "Current_Locale";

    public static $currentLocalization = array();
    public static $defaultLocalization = array();

    public static function get(string $localizationid) {
        if(array_key_exists($localizationid, self::$currentLocalization)) {
            return self::$currentLocalization[$localizationid];
        }
        return self::$defaultLocalization[$localizationid];
    }

    public static function setCurrentLocale(string $locale) {
        $_SESSION[self::PREFIX . self::SUFFIX_CURRENT_LOCALE] = $locale;
    }

    public static function getCurrentLocale() {
        if(!isset($_SESSION[self::PREFIX . self::SUFFIX_CURRENT_LOCALE])) {
            self::setCurrentLocale(self::getDefaultLocale());
        }
        return $_SESSION[self::PREFIX . self::SUFFIX_CURRENT_LOCALE];
    }

    public static function getDefaultLocale() {
        return further\LocalizationStore::LOCALE_ENGLISH;
    }

    public static function loadDefaultLocale() {
        return further\LocalizationStore::loadLocale(self::getDefaultLocale());
    }

    public static function loadCurrentLocale() {
        return further\LocalizationStore::loadLocale(self::getCurrentLocale());
    }
}

LocalizationLogic::$defaultLocalization = LocalizationLogic::loadDefaultLocale();
LocalizationLogic::$currentLocalization = LocalizationLogic::loadCurrentLocale();