<?php namespace tests\api\v1\newsletter;

require_once(__DIR__."/../LocalWebTestCase.php");
require_once(__DIR__."/../../../../../private/api/v1/newsletter/NewsletterController.php");

use tests\api\v1\LocalWebTestCase;
use api\v1\newsletter\NewsletterController;

use exceptions\InvalidMailAddressException;
use exceptions\InvalidNameException;
use exceptions\MailAddressAlreadyRegisteredException;
use exceptions\DatabaseException;

/**
* Tests for the newsletter route of the API
*/
class NewsletterUnitTest extends LocalWebTestCase
{
    /**
    * Tests the constructor when the data manager instance given should create a new instance.
    */
    public function testConstructor_WhenDataManagerGiven_CanCreateNewInstance()
    {
        // Arrange
        $dataManager = $this->createMock('\dataManager\newsletter\INewsletterDataManager');

        // Act
        $newsletterController = new NewsletterController($dataManager);
    }

    /**
     * Tests the constructor when an invalid instance is given should throw an InvalidArgumentException.
     *
     * @expectedException InvalidArgumentException
     */
    public function testConstructor_WhenInvalidInstanceGiven_ShouldThrowInvalidArgumentException()
    {
        // Arrange
        $dataManager = "test";

        // Act
        $newsletterController = new NewsletterController($dataManager);
    }

     /**
     * Tests the constructor when a null argument is given.
     * Should throw an InvalidArgumentException.
     *
     * @expectedException InvalidArgumentException
     */
    public function testConstructor_WhenNullArgumentGiven_ShouldThrowInvalidArgumentException()
    {
        // Act
        $newsletterController = new NewsletterController(null);
    }

    /**
    * Tests the register route when all is ok should call the data manager addMailToNewsletter method.
    */
    public function testRegister_WhenAllOk_ShouldCallNewsletterDataManagerAddMailToNewsletter()
    {
        // Arrange
        $dataManager = $this->createMock('\dataManager\newsletter\INewsletterDataManager');
        $container = $this->app->getContainer();
        $container['NewsletterController'] = function ($container) use ($dataManager) {
            return new NewsletterController($dataManager);
        };
        $parameters = array('name' => 'William Edwards', 'mail' => 'william@edwards.de');

        // Assert
        $dataManager->expects($this->once())->method('addMailToNewsletter')->with("William Edwards", "william@edwards.de");

        // Act
        $this->client->post('/newsletter/register', $parameters);
    }

    /**
    * Tests the register route when all is ok should return the ok state.
    */
    public function testRegister_WhenAllOk_ShouldReturnOkState()
    {
        // Arrange
        $dataManager = $this->createMock('\dataManager\newsletter\INewsletterDataManager');
        $container = $this->app->getContainer();
        $container['NewsletterController'] = function ($container) use ($dataManager) {
            return new NewsletterController($dataManager);
        };
        $parameters = array('name' => 'William Edwards', 'mail' => 'william@edwards.de');
        $expectedResult = array('requestSuccessful' => '1', 'error' => 'NoError');

        // Act
        $this->client->post('/newsletter/register', $parameters);

        // Assert
        $this->assertEquals(200, $this->client->response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), $this->client->response->getBody());
    }

    /**
    * Tests the register route when the given mail is invalid should return the correct error state.
    */
    public function testRegister_WhenInvalidMailGiven_ShouldReturnErrorState()
    {
        // Arrange
        $dataManager = $this->createMock('\dataManager\newsletter\INewsletterDataManager');
        $container = $this->app->getContainer();
        $container['NewsletterController'] = function ($container) use ($dataManager) {
            return new NewsletterController($dataManager);
        };
        $parameters = array('name' => 'William Edwards', 'mail' => 'william@edwards');
        $expectedResult = array('requestSuccessful' => '0', 'error' => 'InvalidMailAddress');
        $dataManager->method('addMailToNewsletter')->will($this->throwException(new InvalidMailAddressException));

        // Act
        $this->client->post('/newsletter/register', $parameters);

        // Assert
        $this->assertEquals(400, $this->client->response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), $this->client->response->getBody());
    }

    /**
    * Tests the register route when a invalid name is given should return the correct error state.
    */
    public function testRegister_WhenInvalidNameGiven_ShouldReturnErrorState()
    {
        // Arrange
        $dataManager = $this->createMock('\dataManager\newsletter\INewsletterDataManager');
        $container = $this->app->getContainer();
        $container['NewsletterController'] = function ($container) use ($dataManager) {
            return new NewsletterController($dataManager);
        };
        $parameters = array('name' => 'William Edwards%', 'mail' => 'william@edwards.de');
        $expectedResult = array('requestSuccessful' => '0', 'error' => 'InvalidName');
        $dataManager->method('addMailToNewsletter')->will($this->throwException(new InvalidNameException));

        // Act
        $this->client->post('/newsletter/register', $parameters);

        // Assert
        $this->assertEquals(400, $this->client->response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), $this->client->response->getBody());
    }

    /**
    * Tests the register route when the mail address is already registered should return the correct error state.
    */
    public function testRegister_WhenMailAddressAlreadyRegistered_ShouldReturnErrorState()
    {
        // Arrange
        $dataManager = $this->createMock('\dataManager\newsletter\INewsletterDataManager');
        $container = $this->app->getContainer();
        $container['NewsletterController'] = function ($container) use ($dataManager) {
            return new NewsletterController($dataManager);
        };
        $parameters = array('name' => 'William Edwards%', 'mail' => 'william@edwards.de');
        $expectedResult = array('requestSuccessful' => '0', 'error' => 'MailAddressAlreadyRegistered');
        $dataManager->method('addMailToNewsletter')->will($this->throwException(new MailAddressAlreadyRegisteredException));

        // Act
        $this->client->post('/newsletter/register', $parameters);

        // Assert
        $this->assertEquals(409, $this->client->response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), $this->client->response->getBody());
    }

    /**
    * Tests the register route when a database error occurred should return the correct error state.
    */
    public function testRegister_WhenDatabaseError_ShouldReturnErrorState()
    {
        // Arrange
        $dataManager = $this->createMock('\dataManager\newsletter\INewsletterDataManager');
        $container = $this->app->getContainer();
        $container['NewsletterController'] = function ($container) use ($dataManager) {
            return new NewsletterController($dataManager);
        };
        $parameters = array('name' => 'William Edwards%', 'mail' => 'william@edwards.de');
        $expectedResult = array('requestSuccessful' => '0', 'error' => 'DatabaseError');
        $dataManager->method('addMailToNewsletter')->will($this->throwException(new DatabaseException));

        // Act
        $this->client->post('/newsletter/register', $parameters);

        // Assert
        $this->assertEquals(503, $this->client->response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), $this->client->response->getBody());
    }

    /**
    * Tests the register route when the mail parameter is missing should return the correct error state.
    */
    public function testRegister_WhenMailParameterMissing_ShouldReturnErrorState()
    {
        // Arrange
        $dataManager = $this->createMock('\dataManager\newsletter\INewsletterDataManager');
        $container = $this->app->getContainer();
        $container['NewsletterController'] = function ($container) use ($dataManager) {
            return new NewsletterController($dataManager);
        };
        $parameters = array('name' => 'William Edwards');
        $expectedResult = array('requestSuccessful' => '0', 'error' => 'MailParameterMissing');
       
        // Act
        $this->client->post('/newsletter/register', $parameters);

        // Assert
        $this->assertEquals(400, $this->client->response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), $this->client->response->getBody());
    }

    /**
    * Tests the register route when the name parameter is missing should return the correct error state.
    */
    public function testRegister_WhenNameParameterMissing_ShouldReturnErrorState()
    {
        // Arrange
        $dataManager = $this->createMock('\dataManager\newsletter\INewsletterDataManager');
        $container = $this->app->getContainer();
        $container['NewsletterController'] = function ($container) use ($dataManager) {
            return new NewsletterController($dataManager);
        };
        $parameters = array('mail' => 'William@Edwards.de');
        $expectedResult = array('requestSuccessful' => '0', 'error' => 'NameParameterMissing');
       
        // Act
        $this->client->post('/newsletter/register', $parameters);

        // Assert
        $this->assertEquals(400, $this->client->response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), $this->client->response->getBody());
    }

    /**
    * Tests the register route when no parameters given should return the correct error state.
    */
    public function testRegister_WhenNoParameterGiven_ShouldReturnErrorState()
    {
        // Arrange
        $dataManager = $this->createMock('\dataManager\newsletter\INewsletterDataManager');
        $container = $this->app->getContainer();
        $container['NewsletterController'] = function ($container) use ($dataManager) {
            return new NewsletterController($dataManager);
        };
        $expectedResult = array('requestSuccessful' => '0', 'error' => 'NoParametersFound');
       
        // Act
        $this->client->post('/newsletter/register');

        // Assert
        $this->assertEquals(400, $this->client->response->getStatusCode());
        $this->assertEquals(json_encode($expectedResult), $this->client->response->getBody());
    }
}
