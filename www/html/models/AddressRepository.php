<?php

/**
 * AddressRepository.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class AddressRepository extends DbRepository
{
    const ERR_MSG_NOT_INPUT_NAME         = '名前を入力してください';
    const ERR_MSG_NOT_NAME_MAX_LENGTH    = '名前は20 文字以内で入力してください';
    const ERR_MSG_NOT_INPUT_ADDRESS      = '住所を入力してください';
    const ERR_MSG_NOT_ADDRESS_MAX_LENGTH = '住所は250 文字以内で入力してください';
    const ERR_MSG_NOT_ID_MAX_LENGTH      = '対象は8 文字以内で入力してください';
    const ERR_MSG_NOT_SETTING_TARGET     = '対象を指定してください';
    const LENGTH_ID_MAX                  = 8;
    const LENGTH_NAME_MAX                = 20;
    const LENGTH_ADDRESS_MAX             = 250;

    /**
     * @param $user_id
     * @param $post
     */
    public function insert($user_id, $post)
    {
        $now = new DateTime();

        $sql = "
            INSERT INTO address(name, address, created_at, updated_at, created_by, updated_by)
                VALUES(:name, :address, :created_at, :updated_at, :created_by, :updated_by)
        ";

        $stmt = $this->execute($sql, array(
          ':name'       => $post["name"],
          ':address'    => $post["address"],
          ':created_at' => $now->format('Y-m-d H:i:s'),
          ':updated_at' => $now->format('Y-m-d H:i:s'),
          ':created_by' => $user_id,
          ':updated_by' => $user_id,
        ));
    }

    /**
     * @param $user_id
     * @param $post
     */
    public function update($user_id, $post)
    {
        $now = new DateTime();

        $sql = "
            UPDATE address SET
              name = :name, address = :address, updated_at = :updated_at, updated_by = :updated_by
            WHERE id = :id
        ";

        $stmt = $this->execute($sql, array(
          ':id'         => $post["id"],
          ':name'       => $post["name"],
          ':address'    => $post["address"],
          ':updated_at' => $now->format('Y-m-d H:i:s'),
          ':updated_by' => $user_id,
        ));
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $sql = "
            DELETE FROM address
            WHERE id = :id
        ";

        $stmt = $this->execute($sql, array(
          ':id' => $id,
        ));
    }

    /**
     * @return array
     */
    public function fetchAllAddress()
    {
        $sql = "
            SELECT a.*
            FROM address a
                ORDER BY a.created_at DESC
        ";

        return $this->fetchAll($sql);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function fetchById($id)
    {
        $sql = "
            SELECT a.*
                FROM address a
                WHERE a.id = :id
        ";

        return $this->fetch($sql, array(
          ':id' => $id,
        ));
    }

    /**
     * @param $user_id
     *
     * @return array
     */
    public function fetchAllByUserId($user_id)
    {
        $sql = "
            SELECT a.*, u.user_name
                FROM status a
                    LEFT JOIN user u ON a.user_id = u.id
                WHERE u.id = :user_id
                ORDER BY a.created_at DESC
        ";

        return $this->fetchAll($sql, array(':user_id' => $user_id));
    }

    /**
     * @param $id
     * @param $user_name
     *
     * @return array
     */
    public function fetchByIdAndUserName($id, $user_name)
    {
        $sql = "
            SELECT a.* , u.user_name
                FROM status a
                    LEFT JOIN user u ON u.id = a.user_id
                WHERE a.id = :id
                    AND u.user_name = :user_name
        ";

        return $this->fetch($sql, array(
          ':id'        => $id,
          ':user_name' => $user_name,
        ));
    }


    /**
     * @param $params
     *
     * @return array
     */
    public function validPost($params)
    {
        $errors = array();

        if ($this->valid->isEmpty($params["name"])) {
            $errors[] = self::ERR_MSG_NOT_INPUT_NAME;
        }

        if ($this->valid->isCharaLengthMax($params["name"], self::LENGTH_NAME_MAX)) {
            $errors[] = self::ERR_MSG_NOT_NAME_MAX_LENGTH;
        }


        if ($this->valid->isEmpty($params["address"])) {
            $errors[] = self::ERR_MSG_NOT_INPUT_ADDRESS;
        }

        if ($this->valid->isCharaLengthMax($params["address"], self::LENGTH_ADDRESS_MAX)) {
            $errors[] = self::ERR_MSG_NOT_ADDRESS_MAX_LENGTH;
        }

        return $errors;
    }

    /**
     * @param $params
     * 
     * @assert (array('id' => '1')) == self::ERR_MSG_NOT_SETTING_TARGET
     *
     * @return array
     */
    public function validDelete($params)
    {
        $errors = array();

        if ($this->valid->isEmpty($params["id"])) {
            $errors[] = self::ERR_MSG_NOT_SETTING_TARGET;
        }

        if ($this->valid->isCharaLengthMax($params["id"], self::LENGTH_ID_MAX)) {
            $errors[] = self::ERR_MSG_NOT_ID_MAX_LENGTH;
        }

        return $errors;
    }

    /**
     * @param $params
     * @param $user
     */
    public function saveAddress($params, $user)
    {
        if (empty($params["id"]) or !$params["id"]) {
            $this->insert($user['id'], $params);
        } else {
            $this->update($user['id'], $params);
        }
    }

}
