<?php namespace tests\api\v1\version;

require_once(__DIR__."/../LocalWebTestCase.php");

use tests\api\v1\LocalWebTestCase;

/**
* Tests for the version route of the API
*/ 
class VersionUnitTest extends LocalWebTestCase
{
    /**
    * Tests the version route when all ok should return correct version.
    */
    public function testVersion_WhenAllOk_ShouldReturnVersion()
    {
        $this->client->get('/version');
        $this->assertEquals(200, $this->client->response->getStatusCode());
        $this->assertEquals($this->app->getContainer()->get('version'), $this->client->response->getBody());
    }
}

?>