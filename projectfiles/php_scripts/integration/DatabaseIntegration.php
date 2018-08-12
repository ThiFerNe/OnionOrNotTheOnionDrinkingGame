<?php

namespace integration;

require_once(__DIR__ . "/../helper/LogHelper.php");

use \helper\LogHelper as LOG;

/**
 * Class to limit instantiations of database connections.
 * Furthermore to simplify the username and password provision.
 * Additionally to separate the access to the database in ReadOnly and FullAccess connections.
 *
 * @package integration
 */
class DatabaseIntegration
{
    /**
     * The private Singleton instance for ReadOnly access to the database.
     *
     * @var null|\PDO
     */
    protected static $readInstance = NULL;

    /**
     * The private Singleton instance for FullAccess access to the database.
     *
     * @var null|\PDO
     */
    protected static $writeInstance = NULL;

    public static function setCredentials(string $database_server_host, int $database_server_port,
                                          string $database_name, string $database_username, string $database_password,
                                          string $database_read_only_username, string $database_read_only_password)
    {
        $file_handle = fopen(__DIR__ . "/DatabaseConnectOverride.php", 'w');
        if ($file_handle === FALSE) {
            return FALSE;
        }
        fwrite($file_handle,
            "<?php\r\n" .
            "global \$databaseReadConnectionString;\r\n" .
            "global \$databaseReadUsername;\r\n" .
            "global \$databaseReadPassword;\r\n" .
            "\r\n" .
            "\$databaseReadConnectionString = \"mysql:host=" . $database_server_host . ";port=" . $database_server_port . ";dbname=" . $database_name . "\";\r\n" .
            "\$databaseReadUsername = \"" . $database_read_only_username . "\";\r\n" .
            "\$databaseReadPassword = \"" . $database_read_only_password . "\";\r\n" .
            "\r\n" .
            "global \$databaseWriteConnectionString;\r\n" .
            "global \$databaseWriteUsername;\r\n" .
            "global \$databaseWritePassword;\r\n" .
            "\r\n" .
            "\$databaseWriteConnectionString = \"mysql:host=" . $database_server_host . ";port=" . $database_server_port . ";dbname=" . $database_name . "\";\r\n" .
            "\$databaseWriteUsername = \"" . $database_username . "\";\r\n" .
            "\$databaseWritePassword = \"" . $database_password . "\";\r\n"
        );
        fclose($file_handle);

        self::closeAll();

        return TRUE;
    }

    public static function removeCredentials()
    {
        unlink(__DIR__ . "/DatabaseConnectOverride.php");
    }

    public static function testConnection()
    {
        try {
            self::getReadInstance(FALSE);
        } catch (\PDOException $e) {
            LOG::ERROR("While testing the read instance...");
            self::closeAll();
            return $e->getMessage();
        }
        try {
            self::getWriteInstance(FALSE);
        } catch (\PDOException $e) {
            LOG::ERROR("While testing the write instance...");
            self::closeAll();
            return $e->getMessage();
        }
        self::closeAll();
        return TRUE;
    }

    /**
     * The Singleton instance retrieve method for ReadOnly access to the database.
     *
     * @return DatabaseIntegration The instance for ReadOnly access to the database
     */
    public static function getReadInstance(bool $handle_error_yourself = TRUE)
    {
        if (self::$readInstance === NULL) {
            try {

                global $databaseReadConnectionString;
                global $databaseReadUsername;
                global $databaseReadPassword;

                $databaseReadConnectionString = "mysql:host=localhost;dbname=onionornottheonion";
                $databaseReadUsername = "root";
                $databaseReadPassword = "";
                if (file_exists(__DIR__ . "/DatabaseConnectOverride.php")) {
                    include(__DIR__ . "/DatabaseConnectOverride.php");
                }
                self::$readInstance = new self(
                    $databaseReadConnectionString,
                    $databaseReadUsername,
                    $databaseReadPassword
                );
            } catch (\PDOException $e) {
                self::$readInstance = NULL;
                LOG::FATAL("Caught PDOException: " . $e->getMessage());
                if ($handle_error_yourself) {
                    (new \views\ErrorDocument500View())->print();
                    http_response_code(500);
                    die();
                } else {
                    throw $e;
                }
            }
        }
        return self::$readInstance;
    }

    /**
     * The Singleton instance retrieve method for FullAccess access to the database.
     *
     * @return DatabaseIntegration The instance for FullAccess access to the database
     */
    public static function getWriteInstance(bool $handle_error_yourself = TRUE)
    {
        if (self::$writeInstance === NULL) {
            try {
                global $databaseWriteConnectionString;
                global $databaseWriteUsername;
                global $databaseWritePassword;
                $databaseWriteConnectionString = "mysql:host=localhost;dbname=onionornottheonion";
                $databaseWriteUsername = "root";
                $databaseWritePassword = "";
                if (file_exists(__DIR__ . "/DatabaseConnectOverride.php")) {
                    include(__DIR__ . "/DatabaseConnectOverride.php");
                }
                self::$writeInstance = new self(
                    $databaseWriteConnectionString,
                    $databaseWriteUsername,
                    $databaseWritePassword
                );
            } catch (\PDOException $e) {
                self::$writeInstance = NULL;
                LOG::FATAL("Caught PDOException: " . $e->getMessage());
                if ($handle_error_yourself) {
                    (new \views\ErrorDocument500View())->print();
                    http_response_code(500);
                    die();
                } else {
                    throw $e;
                }
            }
        }
        return self::$writeInstance;
    }

    public static function closeAll()
    {
        self::$readInstance = NULL;
        self::$writeInstance = NULL;
    }

    /**
     * The private internal PDO connection instantiated inside the constructor and destructed in the destructor.
     *
     * @var null|\PDO
     */
    private $databaseConnection = NULL;

    /**
     * DatabaseIntegration constructor.
     *
     * @param string $connectString The connection string needed for the \PDO connection.
     * @param string $user The username for the connection.
     * @param string $password The password for the connection.
     */
    protected function __construct(string $connectString, string $user, string $password)
    {
        LOG::TRACE("Opening connection to '{$connectString}' with '{$user}':'{$password}'");
        $this->databaseConnection = new \PDO($connectString, $user, $password);
    }

    /**
     * Simple destructor destructing the internal \PDO connection.
     */
    public function __destruct()
    {
        $this->databaseConnection = NULL;
    }

    /**
     * A simple getter for the internal database connection.
     *
     * @return null|\PDO The internal database connection.
     */
    public function getConnection()
    {
        return $this->databaseConnection;
    }
}