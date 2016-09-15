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
    *
    * @throws InvalidArgumentException Is thrown if an argument is null or empty.
    * @throws InvalidNameException Is thrown if the name contains invalid characters. (Allowed are only letters and whitespaces.)
    * @throws InvalidMailException Is thrown if the mail is not in an valid format.
    * @throws MailAddressAlreadyRegisteredException Is thrown if the given mail address is already registered.
    */
    public function addMailToNewsletter($name, $mail);
}
