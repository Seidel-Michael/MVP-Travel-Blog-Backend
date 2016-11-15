<?php
/**
* The index file bootstraps the slim api.
*/

require_once(__DIR__."/../../../private/settings/database.php");

require_once(__DIR__."/../../../vendor/autoload.php");

$composer = json_decode(file_get_contents(__DIR__."/../../../composer.json"));

$app = new \Slim\App(
    array(
      'version'        => $composer->version,
      'mode'           => 'production',
      'settings' => ['displayErrorDetails' => true]

    )
);


require_once(__DIR__."/../../../private/api/v1/app.php");

$container = $app->getContainer();

require_once(__DIR__."/../../../private/api/v1/newsletter/NewsletterController.php");
require_once(__DIR__."/../../../private/dataManager/newsletter/NewsletterDataManager.php");
require_once(__DIR__."/../../../private/dataAdapter/newsletter/MySQLNewsletterDataAdapter.php");
$container['NewsletterController'] = function ($container) use ($dbConnection, $dbUser, $dbPassword) {
    return new api\v1\newsletter\NewsletterController(new dataManager\newsletter\NewsletterDataManager(new dataAdapter\newsletter\MySQLNewsletterDataAdapter($dbConnection, $dbUser, $dbPassword)));
};


$app->run();
