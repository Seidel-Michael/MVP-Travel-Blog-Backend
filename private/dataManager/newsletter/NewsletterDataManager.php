<?php namespace dataManager\newsletter;

require_once("INewsletterDataManager.php");

class NewsletterDataManager implements INewsletterDataManager
{
    public function __construct($newsletterDataAdapter)
    {
        if($newsletterDataAdapter == null)
        {
            throw new \InvalidArgumentException("The newsletterDataAdapter can not be null.");
        }

        if(!$newsletterDataAdapter instanceof \dataAdapter\newsletter\INewsletterDataAdapter)
        {
            throw new \InvalidArgumentException("The newsletterDataAdapter must be a class implementing the INewsletterDataAdapter interface.");
        }
    }


    public function addMailToNewsletter($name, $mail)
    {
        throw new \Exception("Not implemented!");
    }
}

?>