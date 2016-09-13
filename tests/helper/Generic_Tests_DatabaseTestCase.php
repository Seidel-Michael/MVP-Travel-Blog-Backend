<?php namespace tests\helper;

use PHPUnit\Framework\TestCase;

/**
* Generic database testing class which gets the connection info from the phpunit xml configuration.
*/
abstract class Generic_Tests_DatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    /**
    * The PDO instance.
    */
    static private $pdo = null;

    /**
    * The connection instance. 
    */
    private $conn = null;

    /**
    * Creates the connection for the test enviroment.
    */
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }
}
?>