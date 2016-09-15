<?php namespace api\v1\newsletter;

require_once(__DIR__."/../../../dataManager/newsletter/INewsletterDataManager.php");
require_once(__DIR__.'/../../../exceptions/InvalidMailAddressException.php');
require_once(__DIR__.'/../../../exceptions/InvalidNameException.php');
require_once(__DIR__.'/../../../exceptions/MailAddressAlreadyRegisteredException.php');
require_once(__DIR__.'/../../../exceptions/DatabaseException.php');

use exceptions\InvalidMailAddressException;
use exceptions\InvalidNameException;
use exceptions\MailAddressAlreadyRegisteredException;
use exceptions\DatabaseException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
* The newsletter controller connects the api to the the data manager.
*/
class NewsletterController
{
    // Hold the newsletter data manager instance.
    private $newsletterDataManager = null;

    /**
    * Creates a new instance of the NewsletterController class.
    *
    * @param newsletterDataManager The NewsletterDataManager instance to use.
    *
    * @throws InvalidArgumentException Is thrown if the newsletterDataManager is null or the wrong type.
    */
    public function __construct($newsletterDataManager)
    {
        if ($newsletterDataManager == null) {
            throw new \InvalidArgumentException("The newsletterDataManager can not be null.");
        }
        
        if (!$newsletterDataManager instanceof \dataManager\newsletter\INewsletterDataManager) {
            throw new \InvalidArgumentException("The newsletterDataManager must be a class implementing the INewsletterDataManager interface.");
        }
        
        $this->newsletterDataManager = $newsletterDataManager;
    }

    /**
    * Handles the api register request and connects the rquest to the newsletterDataManager.
    */
    public function register(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $parameters = $request->getParsedBody();

        if ($parameters == null) {
            $result = array('requestSuccessful' => '0', 'error' => 'NoParametersFound');
            return $response->withStatus(400)->withJson($result);
        }

        if (!array_key_exists("name", $parameters)) {
            $result = array('requestSuccessful' => '0', 'error' => 'NameParameterMissing');
            return $response->withStatus(400)->withJson($result);
        }

        if (!array_key_exists("mail", $parameters)) {
            $result = array('requestSuccessful' => '0', 'error' => 'MailParameterMissing');
            return $response->withStatus(400)->withJson($result);
        }

        try {
            $this->newsletterDataManager->addMailToNewsletter($parameters["name"], $parameters["mail"]);
        } catch (InvalidMailAddressException $ex) {
            $result = array('requestSuccessful' => '0', 'error' => 'InvalidMailAddress');
            return $response->withStatus(400)->withJson($result);
        } catch (InvalidNameException $ex) {
            $result = array('requestSuccessful' => '0', 'error' => 'InvalidName');
            return $response->withStatus(400)->withJson($result);
        } catch (MailAddressAlreadyRegisteredException $ex) {
            $result = array('requestSuccessful' => '0', 'error' => 'MailAddressAlreadyRegistered');
            return $response->withStatus(409)->withJson($result);
        } catch (DatabaseException $ex) {
            $result = array('requestSuccessful' => '0', 'error' => 'DatabaseError');
            return $response->withStatus(503)->withJson($result);
        }

        $result = array('requestSuccessful' => '1', 'error' => 'NoError');
        return $response->withStatus(200)->withJson($result);
    }
}
