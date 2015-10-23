<?php

class UserRepositoryTest extends PHPUnit_Extensions_Database_TestCase
{
    static private $pdo  = null;
    private        $conn = null;

    final public function getConnection()
    {

        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO(
                  $GLOBALS['DB_DSN'],
                  $GLOBALS['DB_USER'],
                  $GLOBALS['DB_PASSWD']);
            }
            $this->conn
              = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);

        }

        return $this->conn;
    }

    public function getDataSet()
    {
        $data = array();

        return $this->createArrayDataSet($data);
    }

    public function testUser()
    {

        $data_set = $this->createXMLDataSet(dirname(__FILE__) . '/../data/import.xml');

        $this->databaseTester = null;

        $this->getDatabaseTester()->setSetUpOperation($this->getSetUpOperation());
        $this->getDatabaseTester()->setDataSet($data_set);
        $this->getDatabaseTester()->onSetUp($data_set);

        // temp
//        $this->assertEquals(2, $this->getConnection()->getRowCount('user'));
//        $this->assertTableRowCount("user", "2");

        $obj = new UserRepository(self::$pdo);


        ////////////////// validInsert //////////////////

        $params = array('email' => '', 'password' => 'password');
        $res = $obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'aaaaaaaaa', 'password' => 'password');
        $res = $obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'a@a.d', 'password' => 'password');
        $res = $obj->validInsert($params);
//        var_dump($res);
        $this->assertFalse(empty($res));

        $params = array('email' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa@se-project.co.jp', 'password' => 'password');
        $res = $obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano@se-project.co.jp', 'password' => 'password');
        $res = $obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano2@se-project.co.jp', 'password' => '');
        $res = $obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano2@se-project.co.jp', 'password' => 'a');
        $res = $obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano2@se-project.co.jp', 'password' => '1234567890123456789012345678901234567890');
        $res = $obj->validInsert($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano2@se-project.co.jp', 'password' => 'password');
        $res = $obj->validInsert($params);
//        var_dump($res);
        $this->assertTrue(empty($res));


        ////////////////// validAuth //////////////////


        $params = array();
        $res = $obj->validAuth($params);
        $this->assertFalse(empty($res));

        $params = array('email' => '', 'password' => 'password');
        $res = $obj->validAuth($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'aaaaaaaaa', 'password' => 'password');
        $res = $obj->validAuth($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano@se-project.co.jp', 'password' => '');
        $res = $obj->validAuth($params);
        $this->assertFalse(empty($res));

        $params = array('email' => 'takano@se-project.co.jp', 'password' => 'password');
        $res = $obj->validAuth($params);
        $this->assertTrue(empty($res));

    }
}