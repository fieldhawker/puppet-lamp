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


    /**
     * @param null $message
     *
     * @assert (null) == true
     * @assert ('') == true
     * @assert ('a') == false
     * @assert (0) == false
     * @assert (1) == false
     *
     * @return bool
     */
    public function isEmpty($message = null)
    {
        return (!strlen($message));
    }

    /**
     * @param $mail_address
     *
     * @assert ('sample@gmail.com') == 1
     * @assert (null) == 0
     * @assert ('test') == 0
     * @assert ('-aaa@gmail.com') == 0
     *
     * @return int
     */
    public function isMailAddress($mail_address = null)
    {
        return preg_match(self::REGEX_MAIL_ADDRESS, $mail_address);
    }

    /**
     * @param null $message
     * @param int  $min
     * @param int  $max
     *
     * @assert ('a', 0, 2) == true
     * @assert ('a', 1, 2) == true
     * @assert ('a', 0, 1) == true
     * @assert ('a', 1, 1) == false
     *
     * @return bool
     */
    public function isCharaLengthRange($message = null, $min = 0, $max = 0)
    {
        return ($min > strlen($message) || strlen($message) > $max);
    }

    /**
     * @param null $message
     * @param int  $max
     *
     * @assert ('a', 0) == true
     * @assert ('a', 1) == false
     * @assert ('a', 2) == false
     *
     * @return bool
     */
    public function isCharaLengthMax($message = null, $max = 0)
    {
        return (mb_strlen($message) > $max);
    }

}
