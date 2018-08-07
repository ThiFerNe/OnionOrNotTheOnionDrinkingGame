<?php

namespace helper;

/**
 * A small, simple and self-written class for logging information to a file.
 *
 * @package helper
 */
class LogHelper
{
    /**
     * LEVEL_NONE is used to set the LOG_LEVEL.
     * In this case nothing will be logged.
     */
    const LEVEL_NONE = 7000;

    /**
     * LEVEL_FATAL is used to set the LOG_LEVEL.
     * In this case only LEVEL_FATAL will be logged.
     */
    const LEVEL_FATAL = 6000;

    /**
     * LEVEL_ERROR is used to set the LOG_LEVEL.
     * In this case only LEVEL_FATAL and LEVEL_ERROR will be logged.
     */
    const LEVEL_ERROR = 5000;

    /**
     * LEVEL_WARN is used to set the LOG_LEVEL.
     * In this case only LEVEL_FATAL, LEVEL_ERROR and LEVEL_WARN will be logged.
     */
    const LEVEL_WARN = 4000;

    /**
     * LEVEL_INFO is used to set the LOG_LEVEL.
     * In this case only LEVEL_FATAL, LEVEL_ERROR, LEVEL_WARN and LEVEL_INFO will be logged.
     */
    const LEVEL_INFO = 3000;

    /**
     * LEVEL_DEBUG is used to set the LOG_LEVEL.
     * In this case only LEVEL_FATAL, LEVEL_ERROR, LEVEL_WARN, LEVEL_INFO and LEVEL_DEBUG will be logged.
     */
    const LEVEL_DEBUG = 2000;

    /**
     * LEVEL_TRACE is used to set the LOG_LEVEL.
     * In this case only LEVEL_FATAL, LEVEL_ERROR, LEVEL_WARN, LEVEL_INFO, LEVEL_DEBUG and LEVEL_TRACE will be logged.
     */
    const LEVEL_TRACE = 1000;

    /**
     * The path to write all those logs to.
     *
     * @var null|string
     */
    public static $LOGFILE_PATH = NULL;

    /**
     * The given LOG_LEVEL. See the LEVEL_* for further information.
     *
     * @var int
     */
    public static $LOG_LEVEL = self::LEVEL_INFO;

    /**
     * The singleton instance variable.
     *
     * @var null|\helper\LogHelper
     */
    protected static $instance = NULL;

    /**
     * The standard singleton pattern function.
     *
     * @return \helper\LogHelper|null
     */
    public static function getInstance()
    {
        if (self::$instance === NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Static function to log FATAL to the Singleton instance.
     *
     * @param string $message The message to log
     */
    public static function FATAL(string $message)
    {
        self::getInstance()->log_fatal($message);
    }

    /**
     * Static function to log ERROR to the Singleton instance.
     *
     * @param string $message The message to log
     */
    public static function ERROR(string $message)
    {
        self::getInstance()->log_error($message);
    }

    /**
     * Static function to log WARN to the Singleton instance.
     *
     * @param string $message The message to log
     */
    public static function WARN(string $message)
    {
        self::getInstance()->log_warning($message);
    }

    /**
     * Static function to log INFO to the Singleton instance.
     *
     * @param string $message The message to log
     */
    public static function INFO(string $message)
    {
        self::getInstance()->log_info($message);
    }

    /**
     * Static function to log DEBUG to the Singleton instance.
     *
     * @param string $message The message to log
     */
    public static function DEBUG(string $message)
    {
        self::getInstance()->log_debug($message);
    }

    /**
     * Static function to log TRACE to the Singleton instance.
     *
     * @param string $message The message to log
     */
    public static function TRACE(string $message)
    {
        self::getInstance()->log_trace($message);
    }

    /**
     * Containing the file handle if the file is opened.
     *
     * @var bool|null|resource
     */
    private $logfile = NULL;

    /**
     * LogHelper constructor.
     *
     * The static variable \helper\LogHelper::$LOGFILE_PATH has to be set correct!
     */
    public function __construct()
    {
        if (($this->logfile = fopen(self::$LOGFILE_PATH, "a")) === FALSE) {
            error_log("Could not open logfile \"" . self::$LOGFILE_PATH . "\" @ " . __FILE__ . ":" . __LINE__ . "!");
            http_response_code(500);
            echo "Fatal: Could not open logfile \"" . self::$LOGFILE_PATH . "\"!";
            exit;
        }
    }

    /**
     * LogHelper destructor.
     */
    public function __destruct()
    {
        if ($this->logfile !== NULL) {
            fclose($this->logfile);
        }
    }

    /**
     * Non-static function to log FATAL.
     *
     * @param string $message The message to log
     */
    public function log_fatal(string $message)
    {
        if (self::$LOG_LEVEL <= self::LEVEL_FATAL) {
            $this->log("FATAL", $message);
        }
    }

    /**
     * Non-static function to log ERROR.
     *
     * @param string $message The message to log
     */
    public function log_error(string $message)
    {
        if (self::$LOG_LEVEL <= self::LEVEL_ERROR) {
            $this->log("ERROR", $message);
        }
    }

    /**
     * Non-static function to log WARN.
     *
     * @param string $message The message to log
     */
    public function log_warning(string $message)
    {
        if (self::$LOG_LEVEL <= self::LEVEL_WARN) {
            $this->log("WARN ", $message);
        }
    }

    /**
     * Non-static function to log INFO.
     *
     * @param string $message The message to log
     */
    public function log_info(string $message)
    {
        if (self::$LOG_LEVEL <= self::LEVEL_INFO) {
            $this->log("INFO ", $message);
        }
    }

    /**
     * Non-static function to log DEBUG.
     *
     * @param string $message The message to log
     */
    public function log_debug(string $message)
    {
        if (self::$LOG_LEVEL <= self::LEVEL_DEBUG) {
            $this->log("DEBUG", $message);
        }
    }

    /**
     * Non-static function to log TRACE.
     *
     * @param string $message The message to log
     */
    public function log_trace(string $message)
    {
        if (self::$LOG_LEVEL <= self::LEVEL_TRACE) {
            $this->log("TRACE", $message);
        }
    }

    /**
     * Non-static function to log.
     *
     * @param string $level The level to use to log
     * @param string $message The message to log
     */
    public function log(string $level, string $message)
    {
        if (($callingFile = $this->getCallingFile()) === FALSE) {
            $callingFile = "N/A";
        }
        $logLine = date("Y-m-d H:i:s") . " {$level} {$callingFile} - {$message}\r\n";
        fwrite($this->logfile, $logLine);
        fflush($this->logfile);
    }

    /**
     * A little helper function to get the calling file with line number.
     *
     * @return bool|string Either FALSE when failed or the string containing the location data.
     */
    private function getCallingFile()
    {
        $backtrace = debug_backtrace();
        $currentFile = __FILE__;
        for ($i = 0; $i < sizeof($backtrace); $i++) {
            if ($currentFile !== $backtrace[$i]["file"]) {
                $lastDirectorySeparator = strrpos($backtrace[$i]["file"], DIRECTORY_SEPARATOR);
                if ($lastDirectorySeparator !== FALSE && strlen($backtrace[$i]["file"]) > ($lastDirectorySeparator + 1)) {
                    return substr($backtrace[$i]["file"], $lastDirectorySeparator + 1) . ":" . $backtrace[$i]["line"];
                }
            }
        }
        return FALSE;
    }
}