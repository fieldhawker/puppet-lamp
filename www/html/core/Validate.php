<?php

/**
 * Validate.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class Validate
{

    CONST REGEX_MAIL_ADDRESS = '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/';


    /**
     *
     */
    public function __construct()
    {

    }


    public function isEmpty($message = null)
    {
        return (!strlen($message));
    }

    /**
     * @param $mail_address
     *
     * @return int
     */
    public function isMailAddress($mail_address = null)
    {
        return preg_match(self::REGEX_MAIL_ADDRESS, $mail_address);
    }

    public function isCharaLengthRange($message = null, $min = 0, $max = 0)
    {
        return ($min > strlen($message) || strlen($message) > $max);
    }

    public function isCharaLengthMax($message = null, $max = 0)
    {
        return (mb_strlen($message) > $max);
    }

}
