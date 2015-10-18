<?php

/**
 * AddressRepository.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class AddressRepository extends DbRepository
{
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

    public function fetchAllAddress()
    {
        $sql = "
            SELECT a.*
            FROM address a
                ORDER BY a.created_at DESC
        ";

        return $this->fetchAll($sql);
    }

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
}
