<?php namespace tests\dataManager\newsletter;

require_once(__DIR__.'/../../../../private/dataManager/newsletter/NewsletterDataManager.php');

use dataManager\newsletter\NewsletterDataManager;
use exceptions\MailAddressAlreadyRegisteredException;
use PHPUnit\Framework\TestCase;

/**
* The unit tests for the NewsletterDataManager class.
*/
class NewsletterDataManagerUnitTests extends TestCase
{

    /**
    * Tests the constructor when a valid data adapter is given. 
    * Should crate an instance of the object.
    */
    function testConstructor_WhenDataAdapterGiven_CanCreateNewInstance()
    {
        // Arrange
        $dataAdapter = $this->createMock('\dataAdapter\newsletter\INewsletterDataAdapter');

        // Act
        $dataManager = new NewsletterDataManager($dataAdapter);
    }

    /**
     * Test the constructor when an invalid data adater instance is given. 
     * Should throw an InvalidArgumentException. 
     *
     * @expectedException InvalidArgumentException
     */
    function testConstructor_WhenInvalidInstanceGiven_ShouldThrowInvalidArgumentException()
    {
        // Arrange
        $dataAdapter = $this->getMockBuilder('\dataManager\newsletter\INewsletterDataManager')->getMock();

        // Act
        $dataManager = new NewsletterDataManager($dataAdapter);
    }

     /**
     * Tests the constructor when a null argument is given. 
     * Should throw an InvalidArgumentException. 
     * 
     * @expectedException InvalidArgumentException
     */
    function testConstructor_WhenNullArgumentGiven_ShouldThrowInvalidArgumentException()
    {
        // Act
        $dataManager = new NewsletterDataManager(null);
    }

    /**
     * Tests the AddMailToNewsletter function when the name parameter is null. 
     * Should throw an InvalidArgumentException. 
     *
     * @expectedException InvalidArgumentException
     */
    function testAddMailToNewsletter_WhenNameIsNull_ShouldThrowInvalidArgumentException()
    {
        // Arrange
        $dataAdapter = $this->createMock('\dataAdapter\newsletter\INewsletterDataAdapter');
        $dataManager = new NewsletterDataManager($dataAdapter);

        // Act 
        $dataManager->addMailToNewsletter("mail@google.de", null);
    }

    /**
     * Tests the AddMailToNewsletter fucntion when the mail parameter is null. 
     * Should throw an InvalidArgumentException. 
     * 
     * @expectedException InvalidArgumentException
     */
    function testAddMailToNewsletter_WhenMailIsNull_ShouldThrowInvalidArgumentException()
    {
        // Arrange
        $dataAdapter = $this->createMock('\dataAdapter\newsletter\INewsletterDataAdapter');
        $dataManager = new NewsletterDataManager($dataAdapter);

        // Act 
        $dataManager->addMailToNewsletter(null, "Name");
    }

    /**
    * Provides a test dataset of invalid mail addresses.
    */
    public function invalidMailProvider()
    {
        return [
            ["Invalid"],
            ["x@x"],
            ["mail@&%$$.de"],
            ["mail@google.=%"]
        ];
    }
       
    /**
     * Tests the AddMailToNewsletter function when an invalid mail address is given. 
     * Should throw an InvalidMailAddressException. 
     *
     * @expectedException \exceptions\InvalidMailAddressException
     * @dataProvider invalidMailProvider
     */
    function testAddMailToNewsletter_WhenMailHasInvalidFormat_ShouldThrowInvalidMailAddressException($mail)
    {
        // Arrange
        $dataAdapter = $this->createMock('\dataAdapter\newsletter\INewsletterDataAdapter');
        $dataManager = new NewsletterDataManager($dataAdapter);

        // Act 
        $dataManager->addMailToNewsletter("Name", $mail);
    }

    /**
     * Tests the AddMailToNewsletter function when the mail is empty. 
     * Should throw an InvalidArgumentException. 
     *
     * @expectedException InvalidArgumentException
     */
    function testAddMailToNewsletter_WhenMailIsEmpty_ShouldThrowInvalidArgumentException()
    {
        // Arrange
        $dataAdapter = $this->createMock('\dataAdapter\newsletter\INewsletterDataAdapter');
        $dataManager = new NewsletterDataManager($dataAdapter);

        // Act 
        $dataManager->addMailToNewsletter("Name", "");
    }

    /**
    * Provides a test dataset with invalid names.
    */
    public function invalidNameProvider()
    {
        return [
            ["x@x"],
            ["%&@google.de"],
            ["mail@&%$$.de"],
            ["mail@google.=%"]
        ];
    }

    /**
     * Tests the AddMailToNewsletter function when a name with invalid characters is given. 
     * Should throw a InvalidNameException. 
     * 
     * @expectedException \exceptions\InvalidNameException
     * @dataProvider invalidNameProvider
     */
    function testAddMailToNewsletter_WhenNameContainsInvalidCharacters_ShouldThrowInvalidNameException($name)
    {
         // Arrange
        $dataAdapter = $this->createMock('\dataAdapter\newsletter\INewsletterDataAdapter');
        $dataManager = new NewsletterDataManager($dataAdapter);

        // Act 
        $dataManager->addMailToNewsletter($name, "mail@google.de");
    }

    /**
     * Tests the AddMailToNewsletter function when an empty name is given. 
     * Should throw an InvalidArgumentException. 
     *
     * @expectedException InvalidArgumentException
     */
    function testAddMailToNewsletter_WhenNameIsEmpty_ShouldThrowInvalidArgumentException()
    {
         // Arrange
        $dataAdapter = $this->createMock('\dataAdapter\newsletter\INewsletterDataAdapter');
        $dataManager = new NewsletterDataManager($dataAdapter);

        // Act 
        $dataManager->addMailToNewsletter('', "mail@google.de");
    }

    /**
    * Tests the AddMailToNewsletter function when a valid name and mail address given. 
    * Should call the insert function of the data adapter.
    */
    function testAddMailToNewsletter_WhenNameAndMailValid_ShouldCallDataAdapterInsert()
    {

         // Arrange
        $dataAdapter = $this->createMock('\dataAdapter\newsletter\INewsletterDataAdapter');
        $dataManager = new NewsletterDataManager($dataAdapter);

        // Assert
        $dataAdapter->expects($this->once())->method('insertMailAddress')->with("Name", "mail@google.de");

        // Act 
        $dataManager->addMailToNewsletter("Name", "mail@google.de");
    }

    /**
     * Tests the AddMailToNewsletter function when valid data given but the data adapter insert function throws a MailAddressAlreadyRegisteredException. 
     * Should throw a MailAddressAlreadyRegisteredException. 
     *
     * @expectedException \exceptions\MailAddressAlreadyRegisteredException
     */
    function testAddMailToNewsletter_WhenDataAdapterThrowsMailAddressAlreadyRegisteredException_ShouldRethrowException()
    {
        // Arrange
        $dataAdapter = $this->createMock('\dataAdapter\newsletter\INewsletterDataAdapter');
        $dataManager = new NewsletterDataManager($dataAdapter);
        $dataAdapter->method('insertMailAddress')->will($this->throwException(new MailAddressAlreadyRegisteredException));


        // Act 
        $dataManager->addMailToNewsletter("Name", "mail@google.de");
    }
}

?>