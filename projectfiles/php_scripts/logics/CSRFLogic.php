<?php

namespace logics;

require_once(__DIR__ . "/../helper/LogHelper.php");

use \helper\LogHelper as LOG;

/**
 * The function to make CSRF / XSRF prevention easier to use within the controllers.
 *
 * @package logics
 */
class CSRFLogic
{
    /**
     * Suffix to append in the $_SESSION variable to the prefix to save the CSRF value.
     */
    const SUFFIX_VALUE = "CSRFValue";

    /**
     * Suffix to append in the $_SESSION variable to the prefix to save the CSRF end time value.
     */
    const SUFFIX_END_TIME = "CSRFEndTime";

    /**
     * Method to generate the CSRF value.
     * That does not have to be that secure.
     * It's secure enough for us.
     *
     * @return string The generated CSRF value
     */
    private static function generate()
    {
        return sha1(rand());
    }

    /**
     * Used to generate a NEW CSRF value.
     * Puts this value and the end time inside the $_SESSION array.
     * Returns the generated CSRF value.
     *
     * @param string $prefix The prefix to use for storing inside the $_SESSION array
     *                          Normally this is \controllers\AbstractController::getUniqueControllerPrefix()
     * @param int $validForSeconds Sets the valid until value
     *                          Used to add to the current time. 0 or a negative number sets PHP_INT_MAX
     * @return string The CSRF value
     */
    public static function initialize(string $prefix, int $validForSeconds)
    {
        $_SESSION[$prefix . self::SUFFIX_VALUE] = self::generate();
        if ($validForSeconds > 0) {
            $_SESSION[$prefix . self::SUFFIX_END_TIME] = time() + $validForSeconds;
        } else {
            $_SESSION[$prefix . self::SUFFIX_END_TIME] = PHP_INT_MAX;
        }
        return $_SESSION[$prefix . self::SUFFIX_VALUE];
    }

    /**
     * Used to verify a given CSRF value.
     *
     * @param string $valueToCheck The given value to check against the one saved inside the $_SESSION array
     * @param string $prefix The prefix used prior in the initialize() function
     * @return bool TRUE if it matches and the time has been valid, FALSE if not
     */
    public static function check(string $valueToCheck, string $prefix)
    {
        LOG::DEBUG("Checking `{$valueToCheck}`...");
        if ($valueToCheck !== NULL && $valueToCheck !== FALSE && strlen($valueToCheck) >= 0 &&
            $prefix !== NULL && $prefix !== FALSE && strlen($prefix) >= 0) {
            LOG::DEBUG("Checking if SESSION is set for `{$valueToCheck}`");
            if (isset($_SESSION[$prefix . self::SUFFIX_VALUE]) && $_SESSION[$prefix . self::SUFFIX_END_TIME]) {
                LOG::DEBUG("Checking time for `{$valueToCheck}` with `{$_SESSION[$prefix . self::SUFFIX_END_TIME]}` >= `" . time() . "`!");
                if ($_SESSION[$prefix . self::SUFFIX_END_TIME] >= time()) {
                    LOG::DEBUG("Checking equality for `{$valueToCheck}` and `{$_SESSION[$prefix . self::SUFFIX_VALUE]}`!");
                    if ($_SESSION[$prefix . self::SUFFIX_VALUE] === $valueToCheck) {
                        LOG::DEBUG("`{$valueToCheck}` is VALID!");
                        return TRUE;
                    }
                }
            }
        }
        LOG::DEBUG("`{$valueToCheck}` is NOT VALID!");
        return FALSE;
    }

    /**
     * Removes the parameters from the $_SESSION array.
     *
     * @param string $prefix The prefix used prior in initialize() method
     */
    public static function reset(string $prefix)
    {
        unset($_SESSION[$prefix . self::SUFFIX_VALUE]);
        unset($_SESSION[$prefix . self::SUFFIX_END_TIME]);
    }
}