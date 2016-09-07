<?php namespace tests\dataManager\newsletter;

require_once(__DIR__.'/../../../../private/dataManager/newsletter/NewsletterDataManager.php');
require_once(__DIR__.'/../../../../private/dataAdapter/newsletter/INewsletterDataAdapter.php');

use dataManager\newsletter\NewsletterDataManager;

class NewsletterDataManagerUnitTests extends \PHPUnit_Framework_TestCase
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



}

?>