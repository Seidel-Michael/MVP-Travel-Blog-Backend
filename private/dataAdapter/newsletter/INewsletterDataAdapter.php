<?php namespace dataAdapter\newsletter;

/**
* The Newsletter Data Adapter provides the methods to access the Newsletter data.
*/
interface INewsletterDataAdapter
{
    /**
    * Inserts the mail address and the name to the data.
    *
    * @param string $name
    *  The name.
    *
    * @param string $mail
    *  The mail address.
    *
    * @throws DatabaseException Is thrown if something with the database went wrong.
    * @throws MailAddressAlreadyRegisteredException Is thrown if the given mail address is already registered.
    */
    public function insertMailAddress($name, $mail);
}
