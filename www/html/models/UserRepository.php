<?php

/**
 * UserRepository.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class UserRepository extends DbRepository
{
  public function insert($email, $password)
  {
    $password = $this->hashPassword($password);
    $now = new DateTime();

    $sql = "
            INSERT INTO user(email, password, created_at)
                VALUES(:email, :password, :created_at)
        ";

    $stmt = $this->execute($sql, array(
      ':email'      => $email,
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

//    public function fetchAllFollowingsByUserId($user_id)
//    {
//        $sql = "
//            SELECT u.*
//                FROM user u
//                    LEFT JOIN following f ON f.following_id = u.id
//                WHERE f.user_id = :user_id
//        ";
//
//        return $this->fetchAll($sql, array(':user_id' => $user_id));
//    }
}
