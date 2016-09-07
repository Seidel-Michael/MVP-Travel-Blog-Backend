<?php namespace tests\dataManager\newsletter;

require_once(__DIR__.'/../../../../private/dataManager/newsletter/NewsletterDataManager.php');

use dataManager\newsletter\NewsletterDataManager;
use exceptions\MailAddressAlreadyRegisteredException;
use PHPUnit\Framework\TestCase;

class NewsletterDataManagerUnitTests extends TestCase
{

    function testConstructor_WhenDataAdapterGiven_CanCreateNewInstance()
    {
        // Arrange
        $dataAdapter = $this->createMock('\dataAdapter\newsletter\INewsletterDataAdapter');

        // Act
        $dataManager = new NewsletterDataManager($dataAdapter);
    }

    /**
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
     * @expectedException InvalidArgumentException
     */
    function testConstructor_WhenNullArgumentGiven_ShouldThrowInvalidArgumentException()
    {
        // Act
        $dataManager = new NewsletterDataManager(null);
    }

    /**
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