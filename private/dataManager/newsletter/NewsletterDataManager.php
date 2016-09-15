<?php namespace dataManager\newsletter;

require_once(__DIR__.'/INewsletterDataManager.php');
require_once(__DIR__.'/../../dataAdapter/newsletter/INewsletterDataAdapter.php');
require_once(__DIR__.'/../../exceptions/InvalidMailAddressException.php');
require_once(__DIR__.'/../../exceptions/InvalidNameException.php');
require_once(__DIR__.'/../../exceptions/MailAddressAlreadyRegisteredException.php');

use exceptions\InvalidMailAddressException;
use exceptions\InvalidNameException;
use exceptions\MailAddressAlreadyRegisteredException;

/**
* The NewsletterDataManager implementation.
*/
class NewsletterDataManager implements INewsletterDataManager
{
    
    /**
    * Holds the NewsletterDataAdapter instance.
    */
    private $newsletterDataAdapter = null;
    

    /**
    * Creates a new instance of the NewsletterDataManager class.
    *
    * @param newsletterDataAdapter The NewsletterDataAdapter instance to use.
    *
    * @throws InvalidArgumentException Is thrown if the newsletterDataAdapter is null or the wrong type.
    */
    public function __construct($newsletterDataAdapter)
    {
        if ($newsletterDataAdapter == null) {
            throw new \InvalidArgumentException("The newsletterDataAdapter can not be null.");
        }
        
        if (!$newsletterDataAdapter instanceof \dataAdapter\newsletter\INewsletterDataAdapter) {
            throw new \InvalidArgumentException("The newsletterDataAdapter must be a class implementing the INewsletterDataAdapter interface.");
        }
        
        $this->newsletterDataAdapter = $newsletterDataAdapter;
    }
    
    
    /**
    * Adds the mail address and name to the data.
    *
    * @param string $name
    *  The name.
    *
    * @param string $mail
    *  The mail address.
    *
    * @throws InvalidArgumentException Is thrown if an argument is null or empty.
    * @throws InvalidNameException Is thrown if the name contains invalid characters. (Allowed are only letters and whitespaces.)
    * @throws InvalidMailException Is thrown if the mail is not in an valid format.
    * @throws MailAddressAlreadyRegisteredException Is thrown if the given mail address is already registered.
    */
    public function addMailToNewsletter($name, $mail)
    {
        if ($name == null) {
            throw new \InvalidArgumentException("The parameter name can not be null.");
        }
        
        if ($mail == null) {
            throw new \InvalidArgumentException("The parameter mail can not be null.");
        }
        
        if (!filter_var($name, \FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-ZöäüÖÄÜ\s]*$/")))) {
            throw new InvalidNameException("The name ($name) has invalid characters. Only A-z and whitespaces are allowed");
        }
        
        if (!filter_var($mail, \FILTER_VALIDATE_EMAIL)) {
            throw new InvalidMailAddressException("The mail address ($mail) has an invalid format.");
        }
        
        if (strlen($name) > 254) {
            throw new InvalidNameException("The name can't be longer than 254 characters including whitespaces.");
        }

        try {
            $this->newsletterDataAdapter->insertMailAddress($name, $mail);
        } catch (MailAddressAlreadyRegisteredException $e) {
            throw $e;
        }
    }
}
