<?php namespace tests\dataAdapter\newsletter;

require_once(__DIR__.'/../../../../private/dataAdapter/newsletter/MySQLNewsletterDataAdapter.php');
require_once(__DIR__.'/../../../helper/Generic_Tests_DatabaseTestCase.php');

use tests\helper\Generic_Tests_DatabaseTestCase;
use dataAdapter\newsletter\MySQLNewsletterDataAdapter;
use exceptions\MailAddressAlreadyRegisteredException;
use exceptions\DatabaseException;

/**
* The tests for the MySQLNewsletterDataAdapter class. 
*/
class MySQLNewsletterDataAdapterUnitTests extends Generic_Tests_DatabaseTestCase
{
   
    /**
     * Creates the intial database testing state. 
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__.'/MySQLNewsletterDataAdapterUnitTests.initialDataSet.xml');
    }

    /**
    * Tests the InsertMailAddress method when all is ok should insert the correct data into the database.
    */
    public function testInsertMailAddress_WhenAllOk_ShouldInsertCorrectData()
    {
        // Arrange
        $dataAdapter = new MySQLNewsletterDataAdapter($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);

        // Act
        $dataAdapter->insertMailAddress("Michael", "michael@test.de");

        // Assert
        $queryTable = $this->getConnection()->createQueryTable('newsletteraddressee', 'SELECT * FROM newsletteraddressee');
        $expectedTable = $this->createFlatXmlDataSet(__DIR__."/MySQLNewsletterDataAdapterUnitTestsTestData/testInsertMailAddress_WhenAllOk_ShouldInsertCorrectData.xml");
        $expectedTableFixed = new \PHPUnit_Extensions_Database_DataSet_ReplacementDataSet($expectedTable, array('NOW' => date('Y-m-d H:i:s')));
        $this->assertTablesEqual($expectedTableFixed->getTable("newsletteraddressee"), $queryTable);
    }

    /**
    * Tests the InsertMailAddress method when the name already exists in the database should insert the correct data anyway. 
    */
    public function testInsertMailAddress_WhenNameAlreadyExists_ShouldInsertCorrectData()
    {
        // Arrange
        $dataAdapter = new MySQLNewsletterDataAdapter($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);

        // Act
        $dataAdapter->insertMailAddress("John", "michael@test.de");

        // Assert
        $queryTable = $this->getConnection()->createQueryTable('newsletteraddressee', 'SELECT * FROM newsletteraddressee');
        $expectedTable = $this->createFlatXmlDataSet(__DIR__."/MySQLNewsletterDataAdapterUnitTestsTestData/testInsertMailAddress_WhenNameAlreadyExists_ShouldInsertCorrectData.xml");
        $expectedTableFixed = new \PHPUnit_Extensions_Database_DataSet_ReplacementDataSet($expectedTable, array('NOW' => date('Y-m-d H:i:s')));
        $this->assertTablesEqual($expectedTableFixed->getTable("newsletteraddressee"), $queryTable);
    }

    /**
     * Tests the InsertMailAddress methdod when the mail already exists in the database should throw a MailAddressAlreadyRegisteredException.
     *
     * @expectedException \exceptions\MailAddressAlreadyRegisteredException
     */
    public function testInsertMailAddress_WhenMailAlreadyExists_ShouldThrowMailAddressAlreadyRegisteredException()
    {
        // Arrange
        $dataAdapter = new MySQLNewsletterDataAdapter($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);

        // Act
        $dataAdapter->insertMailAddress("Michael", "info@john.de");
    }

    /**
     * Tests the constructor when the database is not reachable should throw DatabaseException. 
     *
     * @expectedException \exceptions\DatabaseException
     */
    public function testConstructor_WhenDatabaseNotReachable_ShouldThrowDatabaseException()
    {
        // Act
        $dataAdapter = new MySQLNewsletterDataAdapter("mysql:dbname=mvptravelblog;host=google.de", $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
    }

    /**
     * Tests the constructor when the database login is invalid should throw a DatabaseException. 
     *
     * @expectedException \exceptions\DatabaseException
     */
    public function testConstructor_WhenDatabaseLoginWrong_ShouldThrowDatabaseException()
    {
        // Act
        $dataAdapter = new MySQLNewsletterDataAdapter($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], "wrong");
    }


    /**
     * Tests the constructor when the dbConnection argument is null should throw InvalidArgumentException.
     *
     * @expectedException InvalidArgumentException
     */
    public function testConstructor_WhenDBConnectionArgumentIsNull_ShouldThrowInvalidArgumentException()
    {
        // Act
        $dataAdapter = new MySQLNewsletterDataAdapter(null, $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
    }

    /**
     * Tests the constructor when the dbUser argument is null should throw InvalidArgumentException.
     *
     * @expectedException InvalidArgumentException
     */
    public function testConstructor_WhenDBUserArgumentIsNull_ShouldThrowInvalidArgumentException()
    {
        // Act
        $dataAdapter = new MySQLNewsletterDataAdapter($GLOBALS['DB_DSN'], null, $GLOBALS['DB_PASSWD']);
    }

    /**
     * Tests the constructor when the dbPassword argument is null should throw InvalidArgumentException.
     *
     * @expectedException InvalidArgumentException
     */
    public function testConstructor_WhenDBPasswordArgumentIsNull_ShouldThrowInvalidArgumentException()
    {
        // Act
        $dataAdapter = new MySQLNewsletterDataAdapter($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], null);
    }

}


?>