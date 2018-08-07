<?php

namespace exceptions;

/**
 * Specific exception class to use when getting the error array from \PDO::errorInfo().
 *
 * @package exceptions
 */
class SimplePDOException extends \Exception
{
    /**
     * Containing the value given in the constructor.
     *
     * @var array|null
     */
    private $pdoErrorInfo = NULL;

    /**
     * SimplePDOException constructor.
     *
     * @param array $pdoErrorInfo The returned value from \PDO::errorInfo()
     */
    public function __construct(array $pdoErrorInfo)
    {
        parent::__construct($pdoErrorInfo[2]);
        $this->pdoErrorInfo = $pdoErrorInfo;
    }

    /**
     * Returns the SQLSTATE error code (a five characters alphanumeric identifier defined in the ANSI SQL standard).
     *
     * @return string The SQLSTATE error code
     */
    public function getSqlStateErrorCode()
    {
        return $this->pdoErrorInfo[0];
    }

    /**
     * Returns the Driver-specific error code.
     *
     * @return int The Driver-specific error code
     */
    public function getDriverSpecificErrorCode()
    {
        return $this->pdoErrorInfo[1];
    }

    /**
     * Returns the Driver-specific error message.
     *
     * @return string The Driver-specific error message
     */
    public function getDriverSpecificErrorMessage()
    {
        return $this->pdoErrorInfo[2];
    }
}