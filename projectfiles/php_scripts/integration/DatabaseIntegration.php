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

    /**
     * The Singleton instance retrieve method for ReadOnly access to the database.
     *
     * @return DatabaseIntegration The instance for ReadOnly access to the database
     */
    public static function getReadInstance()
    {
        if (self::$readInstance === NULL) {
            try {

                global $databaseReadConnectionString;
                global $databaseReadUsername;
                global $databaseReadPassword;

                $databaseReadConnectionString = "mysql:host=db-training.informatik.fh-nuernberg.de;dbname=db18_neumannth";
                $databaseReadUsername = "db18_neumannth";
                $databaseReadPassword = "db18_neumannth";
                if(file_exists(__DIR__ . "/DatabaseConnectOverride.php")) {
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
                (new \views\ErrorDocument500View())->print();
                http_response_code(500);
                die();
            }
        }
        return self::$readInstance;
    }

    /**
     * The Singleton instance retrieve method for FullAccess access to the database.
     *
     * @return DatabaseIntegration The instance for FullAccess access to the database
     */
    public static function getWriteInstance()
    {
        if (self::$writeInstance === NULL) {
            try {
                global $databaseWriteConnectionString;
                global $databaseWriteUsername;
                global $databaseWritePassword;
                $databaseWriteConnectionString = "mysql:host=db-training.informatik.fh-nuernberg.de;dbname=db18_neumannth";
                $databaseWriteUsername = "db18_neumannth";
                $databaseWritePassword = "db18_neumannth";
                if(file_exists(__DIR__ . "/DatabaseConnectOverride.php")) {
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
                (new \views\ErrorDocument500View())->print();
                http_response_code(500);
                die();
            }
        }
        return self::$writeInstance;
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