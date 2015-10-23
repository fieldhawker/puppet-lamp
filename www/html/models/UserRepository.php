<?php

/**
 * UserRepository.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class UserRepository extends DbRepository
{

    const ERR_MSG_NOT_INPUT_MAIL_ADDRESS = 'メールアドレスを入力してください';
    const ERR_MSG_NOT_MAIL_FORMAT        = 'メールアドレスはメール形式で入力してください';
    const ERR_MSG_NOT_RANGE_MAIL_ADDRESS = 'メールアドレスは6 ～ 256 文字以内で入力してください';
    const ERR_MSG_USED_MAIL_ADDRESS      = 'メールアドレスは既に使用されています';
    const ERR_MSG_NOT_INPUT_PASSWORD     = 'パスワードを入力してください';
    const ERR_MSG_NOT_RANGE_PASSWORD     = 'パスワードは4 ～ 30 文字以内で入力してください';
    const LENGTH_MAIL_ADDRESS_MIN        = 6;
    const LENGTH_MAIL_ADDRESS_MAX        = 256;
    const LENGTH_PASSWORD_MIN            = 4;
    const LENGTH_PASSWORD_MAX            = 30;

    public function insert($params)
    {
        $password = $this->hashPassword($params["password"]);
        $now = new DateTime();

        $sql = "
            INSERT INTO user(email, password, created_at)
                VALUES(:email, :password, :created_at)
        ";

        $stmt = $this->execute($sql, array(
          ':email'      => $params["email"],
          ':password'   => $password,
          ':created_at' => $now->format('Y-m-d H:i:s'),
        ));
    }

    public function hashPassword($password)
    {
        return sha1($password . 'SecretKey');
    }

    public function fetchByEmail($email)
    {
        $sql = "SELECT * FROM user WHERE email = :email";

        return $this->fetch($sql, array(':email' => $email));
    }

    public function isUniqueEmail($email)
    {
        $sql = "SELECT COUNT(id) as count FROM user WHERE email = :email";

        $row = $this->fetch($sql, array(':email' => $email));
        if ($row['count'] === '0') {
            return true;
        }

        return false;
    }

    /**
     * @param $params
     *
     * @return array
     */
    public function validInsert($params)
    {
        $errors = array();

        if ($this->valid->isEmpty($params["email"])) {
            $errors[] = self::ERR_MSG_NOT_INPUT_MAIL_ADDRESS;
        }

        if (!$this->valid->isMailAddress($params["email"])) {
            $errors[] = self::ERR_MSG_NOT_MAIL_FORMAT;
        }

        if (!$this->valid->isCharaLengthRange(
          $params["email"],
          self::LENGTH_MAIL_ADDRESS_MIN,
          self::LENGTH_MAIL_ADDRESS_MAX)
        ) {
            $errors[] = self::ERR_MSG_NOT_RANGE_MAIL_ADDRESS;
        }

        if (count($errors) === 0 && !$this->isUniqueEmail($params["email"])) {
            $errors[] = self::ERR_MSG_USED_MAIL_ADDRESS;
        }

        if ($this->valid->isEmpty($params["password"])) {
            $errors[] = self::ERR_MSG_NOT_INPUT_PASSWORD;
        }

        if (!$this->valid->isCharaLengthRange(
          $params["password"],
          self::LENGTH_PASSWORD_MIN,
          self::LENGTH_PASSWORD_MAX)
        ) {
            $errors[] = self::ERR_MSG_NOT_RANGE_PASSWORD;
        }

        return $errors;
    }

    /**
     * @param $params
     *
     * @return array
     */
    public function validAuth($params)
    {
        $errors = array();

        if ($this->valid->isEmpty($params["email"])) {
            $errors[] = self::ERR_MSG_NOT_INPUT_MAIL_ADDRESS;
        }

        if (!$this->valid->isMailAddress($params["email"])) {
            $errors[] = self::ERR_MSG_NOT_MAIL_FORMAT;
        }


        if ($this->valid->isEmpty($params["password"])) {
            $errors[] = self::ERR_MSG_NOT_INPUT_PASSWORD;
        }

        return $errors;
    }

}
