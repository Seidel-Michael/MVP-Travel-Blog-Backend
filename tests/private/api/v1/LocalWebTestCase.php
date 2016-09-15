<?php namespace tests\api\v1;

require_once(__DIR__ . '/../../../../vendor/autoload.php');

use There4\Slim\Test\WebTestCase;

/**
* The class provices the basic bootstrapping for the slim unit tests.
*/
class LocalWebTestCase extends WebTestCase
{

    /**
    * Creates the slim instance.
    */
    public function getSlimInstance()
    {
        $app = new \Slim\App(array(
          'version'        => '0.0.0',
          'debug'          => false,
          'mode'           => 'testing',
      ));

        require __DIR__ . '/../../../../private/api/v1/app.php';
        return $app;
    }
};
