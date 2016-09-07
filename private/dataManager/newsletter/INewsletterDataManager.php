<?php namespace dataManager\newsletter;

/**
* The Newsletter Data Manager connects the public api with the data layer.
*/
interface INewsletterDataManager
{
    /**
    * Adds the mail address and name to the data. 
    *
    * @param string $name 
    *  The name.
    *
    * @param string $mail
    *  The mail address. 
    */
    public function addMailToNewsletter($name, $mail);
}

?>